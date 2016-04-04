<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/8
 * Time: 9:05
 */

namespace Admin\Controller;
use Common\Service\NewsService;
use Common\Service\ImageService;

class NewsController extends CommonController{
    public function newsList(){
        $itemNumPerPage = 3;
        $news = M("news");
        $count = $news->count();
        $Page = new \Think\Page($count,$itemNumPerPage);
        $show = $Page->show();// 分页显示输出
        $newsList = $news->order("create_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('newsList', $newsList);
        $this->display();
    }

    private function getEmptyNews(){
        $news["id"] = C("NEW_NEWS");//当id为-1时，表示新插入数据
        $news['title'] = "";
        $news['author'] = "";
        $news['abstract'] = "";
        $news['content'] = "";
        $news['img_name'] = md5(uniqid(rand()));
        return $news;
    }

    public function editNews(){
        $newsId = I("id");
        if( $newsId ){//如果id被传过来了，就是修改news
            $news = NewsService::getNews($newsId);
            $this->assign("imgInit", C("ABS_IMAGE_PATH").$news['img_name']);
        }else{
            $news = $this->getEmptyNews();
            $this->assign("imgInit",C("IMG_NO_PIC"));
        }
        $imgName = md5(uniqid(rand())).".jpg";
        $this->assign("imgViewPath", C("ABS_IMAGE_PATH").$imgName);
        $this->assign("tmpImgName", "tmp_".$imgName);//上传完整图的名称
        $this->assign("imgName", $imgName);//图片截取后的名称
        $this->assign("news", $news);
        $this->display();
    }

    public function editNewsHandler(){
        $id = I("id");
        $data['title'] = I("title");
        $data['author'] = I("author");
        $data['img_name'] = I("img");
        $data['abstract'] = I("abstract");
        $data['content'] = I("content");
        $imgTmpFile = I("imgTmpFile");//暂存的图片文件
        $imgData = C("IMAGE_PATH").$data['img_name'];//永久保存的图片文件
        if ($id == C("NEW_NEWS")) {//如果是插入news
            if (M("news")->add($data)) {
                $this->success("添加推送成功！", U('newsList'));
            }else{
                $this->error("添加推送失败！");
            }
        }else{//如果是修改news
            $data['id'] = $id;
            if( M("news")->save($data)){
                $this->success("修改推送成功！", U('newsList'));
            }else{
                $this->error("当前推送未被修改！");
            }
        }
    }

    public function deleteNews(){
        $newsId = I("newsId");
        $json = null;
        if( M("news")->where("id=$newsId")->delete()){
             $json['msg'] = true;
             $json['hint'] = "删除成功！";
        }else{
            $json['msg'] = false;
            $json['hint'] = "删除失败！";
        }
        echo json_encode($json);
    }

    public function stickieNews(){
        $newsId = I("newsId");
        $json = null;
        $news = M("news")->where("id=$newsId")->find();
        if( $news ){
            if( $news["create_time"] == C("STICKIE_TIME")){
                $json['msg'] = false;
                $json['hint'] = "该推送已经被置顶了，不能重复置顶！";
            }else{
                $news["create_time"] = C("STICKIE_TIME");
                if (M("news")->save($news)) {
                    $json['msg'] = true;
                    $json['hint'] = "置顶成功！";
                }else{
                    $json['msg'] = false;
                    $json['hint'] = "置顶失败！";
                }
            }
        }else{
            $json['msg'] = false;
            $json['hint'] = "置顶失败！";
        }
        echo json_encode($json);
    }

    public function unstickieNews(){
        $newsId = I("newsId");
        $json = null;
        $news = M("news")->where("id=$newsId")->find();
        if( $news && $news['create_time'] == C("STICKIE_TIME")){
            $news['create_time'] = $news["update_time"];
            if (M("news")->save($news)) {
                $json['msg'] = true;
                $json['hint'] = "取消置顶成功！";
            }else{
                $json['msg'] = false;
                $json['hint'] = "取消置顶失败！";
            }
        }else{
            $json['msg'] = false;
            $json['hint'] = "该推送未被置顶！";
        }
        echo json_encode($json);
    }

    /**
     * Jcrop截图处理后台
     */
    public function jcrop(){
        $image = new \Think\Image();
        $tmpImagePath =  C("TMP_PATH").I("tmpImgName");
        $imagePath =  C("IMAGE_PATH").I("imgName");
        $jcropWidth = C("JCROP_IMAGE_WIDTH");
        $jcropHeight = C("JCROP_IMAGE_HEIGHT");
        $image->open($tmpImagePath);
        $image->crop($_POST['w'],$_POST['h'],$_POST['x'],$_POST['y'])
            ->thumb($jcropWidth,$jcropHeight,\Think\Image::IMAGE_THUMB_FIXED)->save($imagePath);
        unlink($tmpImagePath);
        $ret['error_code'] = 0;
        echo json_encode($ret);
    }

    public function uploadHandler(){
        $tmpImgName = I("tmpImgName");
        $path = iconv('utf-8','gb2312',"Upload/tmp/".$tmpImgName);
        $absPath = C("ABS_TMP_PATH").$tmpImgName;
        move_uploaded_file($_FILES['upload_file']['tmp_name'], $path);
        $thumbPath = C("TMP_PATH").$tmpImgName;//为了使用image，转换为相对路径
        ImageService::thumb(870, $thumbPath, $thumbPath);
        echo "<textarea><img src='{$absPath}' id='cropbox' /></textarea>";
    }

}