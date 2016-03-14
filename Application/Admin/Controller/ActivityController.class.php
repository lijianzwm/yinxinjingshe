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

    public function addActivity(){
        $activity = ActivityService::getEmptyActivity();
        $this->assign("activity", $activity);
        $this->display("editActivity");
    }

    public function modifyActivity(){
        $activity = ActivityService::getActivity(I("id"));
        $this->assign("activity", $activity);
        $this->display("editActivity");
    }

    public function editActivityHandler(){
        $id = I("id");
        $activity["title"] = I("title");
        $activity["img_name"] = I("imgName");
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
}