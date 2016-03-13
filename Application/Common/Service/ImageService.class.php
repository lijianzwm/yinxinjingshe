<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/12
 * Time: 17:40
 */

namespace Common\Service;
use Think\Storage\Driver;

/**
 * Class PersistenceService
 * @package Common\Service
 * 持久化服务
 */
class ImageService{

    public static function persistence($tmpData, $realPath ){
        if( rename($tmpData,$realPath ) ){
            return true;
        }else{
            return false;
        }
    }

    public static function delete( $img ){
//        if (unlink($img)) {
//            return true;
//        }else{
//            return false;
//        }
    }

}