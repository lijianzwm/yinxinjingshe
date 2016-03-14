<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/13
 * Time: 18:08
 */

namespace Admin\Controller;
use Common\Service\VideoService;

class VideoController extends CommonController{

    public function videoSetList(){
        $videoSetList = VideoService::getVideoSetList();
        $this->assign("videoSetList", $videoSetList);
        $this->display();
    }

    public function addVideoSet(){
        $videoSet = VideoService::getEmptyVideoSet();
        $this->assign("videoSet", $videoSet);
        $this->display("editVideoSet");
    }

    public function modifyVideoSet(){
        $videoSet = VideoService::getvideoSet(I("setId"));
        $this->assign("videoSet", $videoSet);
        $this->display("editVideoSet");
    }

    public function editVideoSetHandler(){
        $id = I("setId");
        $video["name"] = I("name");
        $video["author"] = I("author");
        if ($id == C("NEW_VIDEO")) {
            if( M("video_set_list")->add($video) ){
                $this->success("添加成功！", U("videoSetList"));
            }else{
                $this->error("添加失败！");
            }
        }else{
            $video["set_id"] = I("setId");
            if( M("video_set_list")->save($video) ){
                $this->success("修改成功！", U("videoSetList"));
            }else{
                $this->error("信息未被修改！");
            }
        }
    }

    public function videoSet(){
        $videoSet = VideoService::getVideoSet(I("setId"));
        $this->assign("videoSet", $videoSet);
        $this->assign("setId", I("setId"));
        $this->display();
    }

    public function addVideo(){
        $video = VideoService::getEmptyVideo(I("setId"));
        $this->assign("video", $video);
        $this->display( "editVideo" );
    }

    public function modifyVideo(){
        $video = VideoService::getVideo(I("videoId"));
        $this->assign("video", $video);
        $this->display("editVideo");
    }

    public function editVideoHandler(){
        $id = I("videoId");
        $video["set_id"] = I("setId");
        $video["sort_num"] = I("sortNum");
        $video["name"] = I("name");
        $video["content"] = I("content");
        if ($id == C("NEW_VIDEO")) {
            if( M("video")->add($video)){
                $this->success("添加视频成功！", U("videoSet", array('setId'=>$video["set_id"])));
            }else{
                $this->error("添加视频失败！");
            }
        }else{
            $video["video_id"] = $id;
            if (M("video")->save($video)) {
                $this->success("修改视频成功！", U("videoSet", array('setId' => $video["set_id"])));
            }else{
                $this->error("视频内容未被修改！");
            }
        }
    }

    public function deleteVideo(){
        $videoId = I("videoId");
        $json = null;
        if( M("video")->where("video_id='$videoId'")->delete()){
            $json['msg'] = true;
        }else{
            $json['msg'] = false;
        }
        echo json_encode($json);
    }

}
