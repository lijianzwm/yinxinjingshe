<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/12
 * Time: 17:40
 */

namespace Common\Service;

/**
 * Class PersistenceService
 * @package Common\Service
 * 持久化服务
 */
class PersistenceService{

    public static function persistence($tmpData, $realPath ){
        if (copy($tmpData, $realPath)) {
            if( unlink($tmpData) ){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

}