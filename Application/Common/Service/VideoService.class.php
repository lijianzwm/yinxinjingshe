<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/13
 * Time: 18:18
 */

namespace Common\Service;


class VideoService{
    /**
     * @return mixed
     * 获取所有视频集名字列表
     */
    public static function getVideoSetList( $author=null ){
        if( isset($author)){
            return M("video_set_list")->where("author='$author'")->select();
        }else{
            return M("video_set_list")->select();
        }
    }

    /**
     * @param $setId
     * @return mixed
     * 获取视频集的视频列表
     */
    public static function getVideoSet( $setId ){
        return M("video")->where("set_id='$setId'")->order("sort_num")->select();
    }

    public static function getEmptyVideoSet(){
        $video["set_id"] = C("NEW_VIDEO");
        $video["name"] = "";
        $video["author"] = "";
        return $video;
    }

    public static function getVideo( $videoId ){
        $video = M("video");
        return $video->table("xzx_video v, xzx_video_set_list s")->where("v.video_id='$videoId' and s.set_id = v.set_id")
                ->field("v.video_id, v.set_id, v.sort_num, v.name, v.content, s.name as father")->find();
    }

    public static function getEmptyVideo($setId){
        $video["video_id"] = C("NEW_VIDEO");
        $video["father"] = M("video_set_list")->field("name")->where("set_id='$setId'")->find()["name"];
        $video["set_id"] = $setId;
        $maxSortNum = M("video")->where("set_id='$setId'")->order("sort_num desc")->select();
        if( !$maxSortNum ){
            $video["sort_num"] = 1;
        }else{
            $video["sort_num"] = $maxSortNum["sort_num"];
        }
        $video["name"] = "";
        $video["content"] = "";
        return $video;
    }

}