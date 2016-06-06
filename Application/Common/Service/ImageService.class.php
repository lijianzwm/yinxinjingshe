<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/4/2
 * Time: 16:52
 */

namespace Common\Service;


class ImageService{

    /**
     * 生成长款最大不超过$maxScopLen的缩略图
     * @param $maxScopLen
     * @param $inputPath
     * @param $outputPath
     */
    public static function thumb( $maxScopLen, $inputPath, $outputPath ){
        $image = new \Think\Image();
        $image->open($inputPath);
        $width = $image->width(); // 返回图片的宽度
        $height = $image->height(); // 返回图片的高度
        if( $width > $height ){//扁的图片
            if( $width > $maxScopLen ){
                $newWidth = $maxScopLen;
                $newHeight = floor(($maxScopLen/$width)*$height);
            }else{
                $newWidth = $width;
                $newHeight = $height;
            }
        }else{//长的图片
            if( $height > $maxScopLen ){
                $newHeight = $maxScopLen;
                $newWidth = floor(($maxScopLen/$height)*$width);
            }else {
                $newWidth = $width;
                $newHeight = $height;
            }
        }
        $image->thumb($newWidth,$newHeight,\Think\Image::IMAGE_THUMB_FIXED)->save($outputPath);
    }

}