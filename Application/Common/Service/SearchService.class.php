<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/5/13
 * Time: 上午11:33
 */

namespace Common\Service;


class SearchService{

    public static function searchVideo($keyWords){
        $videoSubtitles = C("DB_PREFIX")."video_subtitles";
        $video = C("DB_PREFIX") . "video";

        $result = M()->table("$videoSubtitles subtitles, $video video")
                    ->field("video.video_id as video_id, subtitles.start_time as start_time, video.name as title, subtitles.content as content")
                    ->where("subtitles.video_id = video.video_id and subtitles.content like '%$keyWords%'")->select();
        for( $i = 0; $i < count($result); $i++ ){
            $result[$i]['content'] = str_replace($keyWords, "<strong style='color:red'>$keyWords</strong>", $result[$i]['content']);
            $result[$i]['minSec'] = TimeService::sec2MinSec($result[$i]['start_time']);
        }
        return $result;
    }

    public static function searchBook($keyWords){

    }

    public static function searchDiscuz($keyWords){

    }

}