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
        $chapter = C("DB_PREFIX") . "chapter";
        $book = C("DB_PREFIX") . "book";
        $result = M()->table("$chapter chapter, $book book")
                    ->field("chapter.id as id, chapter.title as chapter_title, chapter.num as num, chapter.book_id as book_id, book.name as book_name, chapter.content as content")
                    ->where("chapter.book_id = book.id and chapter.content like '%$keyWords%'")->select();
        for( $i = 0; $i < count($result); $i++ ){
            $result[$i]['content'] = html_entity_decode($result[$i]['content']);
            $result[$i]['content']= preg_replace("/<(p.*?)>|<(\/p.*?)>|<(\/?br.*?)>|<(img.*?)>/si","",$result[$i]['content']); //过滤p,br,img标签
            $content = "";
            $offset = 0;
            $pos = mb_strpos($result[$i]['content'], $keyWords, $offset, "UTF-8");
            $matchNum = 0;
            while( $pos  != false ){
                $beg = $pos - 15;
                $beg = $beg < 0 ? 0 : $beg;
                $length = 30 + mb_strlen($keyWords, "UTF-8");
                $content .= "...".mb_substr($result[$i]['content'], $beg, $length, "UTF-8" )."...";
                $offset = $pos+1;
                $pos = mb_strpos($result[$i]['content'], $keyWords, $offset, "UTF-8");
                $matchNum += 1;
            }
            $result[$i]['content'] = $content;
            $nums[$i] = $matchNum;
            $result[$i]['content'] = str_replace($keyWords, "<strong style='color:red'>$keyWords</strong>", $result[$i]['content']);
        }
        array_multisort($nums, SORT_DESC, $result);

        return $result;
    }

    public static function searchDiscuz($keyWords){

    }

}