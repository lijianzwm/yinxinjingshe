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
        $tmpData = U("yinxinjingshe/Public/Shearphoto/shearphoto_common/file/shearphoto_file/newsphoto_56e3fdd367c82_736_0.jpg");
        $realPath = U("Upload/Image/aaeb7ef544902a24d9141845c76259c9.jpg");

        dump($tmpData);
        dump($realPath);
        dump(copy($tmpData, $realPath));
        dump(unlink($tmpData));
        die();
        if( rename($tmpData,$realPath ) ){
            return true;
        }else{
            return false;
        }
    }

    public static function delete( $img ){
        if (unlink($img)) {
            return true;
        }else{
            return false;
        }
    }

}