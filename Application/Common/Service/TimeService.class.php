<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 16/6/2
 * Time: 下午3:50
 */

namespace Common\Service;


class TimeService{

    /**
     * 秒转换成分和秒,格式如"125:50"
     * @param $seconds
     * @return string
     */
    public static function sec2MinSec( $seconds ){
        $sec = $seconds % 60;
        $min = ($seconds - $sec)/60;
        return $min.":".$sec;
    }
}