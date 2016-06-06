<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/15
 * Time: 9:19
 */

namespace Api\Controller;


use Think\Controller;
use Common\Service\VideoService;

class VideoController extends Controller{

    /**
     * 获取视频集列表
     */
    public function renderVideoSetList(){
        $videoSetList = VideoService::getVideoSetList();
        echo json_encode($videoSetList);
    }

    /**
     * 获取视频集
     */
    public function renderVideoSet(){
        $videoSet = VideoService::getVideoSet(I("id"));
        echo json_encode($videoSet);
    }

    /**
     * 获取视频
     */
    public function renderVideo(){
        $video = VideoService::getVideo(I("id"));
        echo json_encode($video);
    }
}