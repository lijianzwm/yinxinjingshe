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
        $setId = I("setId");
        $setName = VideoService::getVideoSetName($setId);
        $this->assign("videoSet", $videoSet);
        $this->assign("setId", $setId);
        $this->assign("setName", $setName);
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
        $video["mp4_url"] = I("mp4_url");
        $video["ogg_url"] = I("ogg_url");

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('txt');// 设置附件上传类型
        $upload->rootPath  =     './Upload/'; // 设置附件上传根目录
        $upload->autoSub = false;
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }

        $filePath = './Upload/'.$info["upload_file"]["savename"];

        if ($id == C("NEW_VIDEO")) {
            $videoId = M("video")->add($video);
            if( $videoId && self::subtitleHandler($filePath,$videoId) ){
                $this->success("添加视频成功！", U("videoSet", array('setId'=>$video["set_id"])));
            }else{
                $this->error("添加视频失败！");
            }
        }else{
            $video["video_id"] = $id;
            if (M("video")->save($video) && self::clearSubtitleByVideoId($id) && self::subtitleHandler($filePath,$id) ) {
                $this->success("修改视频成功！", U("videoSet", array('setId' => $video["set_id"])));
            }else{
                $this->error("视频内容未被修改！");
            }
        }
    }

    private function clearSubtitleByVideoId( $videoId ){
        if( M("video_subtitles")->where("video_id=$videoId")->delete() ){
            return true;
        }else{
            $this->error("删除旧字幕文件错误!");
            return false;
        }
    }

    private function subtitleHandler($filePath, $id){
        $handle = fopen($filePath, 'r');
        $curContent = "";
        $startTime = null;
        $videoSubtitles = array();
        while(!feof($handle)){
            $line = iconv('gb2312','utf-8',fgets($handle, 1024));
            $line = trim($line);
            if( is_numeric($line)){
                if( $curContent != "" && $startTime != null){
                    $item['video_id'] = $id;
                    $item['start_time'] = $startTime;
                    $item['content'] = trim($curContent);
                    array_push($videoSubtitles, $item);
                    $curContent = "";
                    $startTime = null;
                }
                $line = iconv('gb2312','utf-8',fgets($handle, 1024));
                $hourMinSec = substr($line,0,strpos($line,","));
                $elem = explode(":",$hourMinSec);
                $hour = intval($elem[0]);
                $min = intval($elem[1]);
                $second = intval($elem[2]);
                $startTime = $hour * 60 * 60 + $min * 60 + $second;
            }else{
                $curContent .= " ".$line;
            }
        }
        unlink($filePath);
        if( M("video_subtitles")->addAll($videoSubtitles) ){
            return true;
        }else{
            $this->error("字幕写入数据库错误!");
            return false;
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
