<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/12
 * Time: 16:38
 */

namespace Admin\Controller;
use Common\Service\ActivityService;

/**
 * Class EventRegistrationController
 * @package Admin\Controller
 * 活动报名
 */
class ActivityController extends CommonController{

    public function activityList(){
        $activityList = M("activity")->select();
        $this->assign("activityList", $activityList);
        $this->display();
    }

    public function editActivity(){
        $activityId = I("id");
        if( $activityId ){//如果id被传过来了，就是修改news
            $activity = ActivityService::getActivity(I("id"));
            $this->assign("imgInit", C("ABS_IMAGE_PATH").$activity['img_name']);
        }else{
            $activity = ActivityService::getEmptyActivity();
            $this->assign("imgInit",C("IMG_NO_PIC"));
        }
        $imgName = md5(uniqid(rand())).".jpg";
        $this->assign("imgViewPath", C("ABS_IMAGE_PATH").$imgName);
        $this->assign("tmpImgName", "tmp_".$imgName);//上传完整图的名称
        $this->assign("imgName", $imgName);//图片截取后的名称
        $this->assign("activity", $activity);
        $this->display();
    }

    public function editActivityHandler(){
        $id = I("id");
        $activity["title"] = I("title");
        $activity["img_name"] = I("img");
        $activity["sponsor"] = I("sponsor");
        $activity["activity_name"] = I("activityName");
        $activity["start_time"] = I("startTime");
        $activity["reg_deadline"] = I("regDeadline");
        $activity["content"] = I("content");
        if ($id == C("NEW_ITEM")) {
            if (M("activity")->add($activity)) {
                $this->success("添加活动成功！", U("activityList"));
            }else{
                $this->error("添加活动失败！");
            }
        }else{
            if (M("activity")->save($activity)) {
                $this->success("修改成功！", U("activityList"));
            }else{
                $this->error("内容未被修改！");
            }
        }
    }

    public function deleteActivity(){
        $id = I("id");
        $json = null;
        if( M("activity")->where("id='$id'")->delete()){
            $json['msg'] = true;
        }else{
            $json['msg'] = false;
        }
        echo json_encode($json);
    }

    /**
     * 推送报名活动
     */
    public function pushActivity(){
        $activityId = I("id");
        $activityModel = M("activity");
        $newsModel = M("news");
        $activity = $activityModel->where("id='$activityId'")->find();
        if( $activity["news_id"] != 0 ){//如果这个消息被推送过了
            $this->error("这个消息已经被推送过了！");
        }
        $news["title"] = $activity["title"];
        $news["content"] = $activity["content"];
        $news["img_name"] = $activity["img_name"];
        $news["author"] = $activity["sponsor"];
        $news["abstract"] = $activity["activity_name"];
        $activityModel->startTrans();//开启事务
        $newsId = $newsModel->add($news);
        if( $newsId ){
            $news["id"] = $newsId;
            $activity['is_pushed'] = C("ACTIVITY_PUSHED");
            $activity['news_id'] = $newsId;
            if( $activityModel->save($activity) ){
                $activityModel->commit();
                $this->assign("news", $news);
                $this->display();
            }else{
                $activityModel->rollback();
                $this->error("设置is_pushed失败！");
            }
        }else{
            $activityModel->rollback();
            $this->error("添加推送失败！");
        }
    }

    /**
     * 删除已经推送的报名活动
     */
    public function deletePushedActivity(){
        $activityId = I("id");
        $activityModel = M("activity");
        $newsModel = M("news");
        $activity = $activityModel->where("id='$activityId'")->find();
        if( !$activity ){
            $this->error("查无此活动！");
        }
        $newsId = $activity['news_id'];
        if( $newsId == 0 ){//如果报名活动未被推送
            $this->error("无法取消未被推送的活动！");
        }
        $news = $newsModel->where("id='$newsId'")->find();
        if( !$news ){
            $this->error("查无此推送！");
        }
        $activity['is_pushed'] = 0;
        $activity["news_id"] = 0;
        $newsModel->startTrans();
        if( $newsModel->where("id='$newsId'")->delete() && $activityModel->save($activity)){
            $newsModel->commit();
            $this->success("取消推送成功！", U("activityList"));
        }else{
            $newsModel->rollback();
            $this->error("取消推送失败！");
        }
    }

}