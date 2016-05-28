<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/5/13
 * Time: 上午9:19
 */

namespace Home\Controller;


use Think\Controller;
use \Common\Service\VideoService;

class VideoController extends Controller{

    public function videoSetList(){
        $videoSetList = VideoService::getVideoSetList();
        $this->assign("videoSetList", $videoSetList);
        $this->display();
    }

    public function videoSet(){
        $list = VideoService::getVideoSet(I("setId"));
        $this->assign("name",I("setName"));
        $this->assign("list", $list);
        $this->display();
    }

    public function playVideo(){
        $video = VideoService::getVideo(I("videoId"));
        $this->assign("video", $video);
        $this->display();
    }

}