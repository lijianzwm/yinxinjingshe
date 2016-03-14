<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/14
 * Time: 10:40
 */

namespace Common\Service;


class ActivityService{
    public static function getActivity($id){
        $activity = M("activity")->where("id='$id'")->find();
        $activity["content"] = html_entity_decode($activity["content"]);
        return $activity;
    }

    public static function getEmptyActivity(){
        $activity["id"] = C("NEW_ITEM");
        $activity["title"] = "";
        $activity["img_name"] = md5(uniqid(rand()));
        $activity["sponsor"] = "";
        $activity["activity_name"] = "";
        $activity["start_time"] = "";
        $activity["reg_deadline"] = "";
        $activity["content"] = "";
        return $activity;
    }

}