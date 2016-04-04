<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/4/4
 * Time: 17:24
 */

namespace Admin\Controller;
use Common\Service\ImageService;
use Think\Controller;

class ImageController extends Controller{
    /**
     * Jcrop截图处理后台
     */
    public function jcrop(){
        $image = new \Think\Image();
        $tmpImagePath =  C("TMP_PATH").I("tmpImgName");
        $imagePath =  C("IMAGE_PATH").I("imgName");
        $jcropWidth = C("JCROP_IMAGE_WIDTH");
        $jcropHeight = C("JCROP_IMAGE_HEIGHT");
        $image->open($tmpImagePath);
        $image->crop($_POST['w'],$_POST['h'],$_POST['x'],$_POST['y'])
            ->thumb($jcropWidth,$jcropHeight,\Think\Image::IMAGE_THUMB_FIXED)->save($imagePath);
        unlink($tmpImagePath);
        $ret['error_code'] = 0;
        echo json_encode($ret);
    }

    /**
     * 图片上传处理
     */
    public function uploadHandler(){
        $tmpImgName = I("tmpImgName");
        $path = iconv('utf-8','gb2312',"Upload/tmp/".$tmpImgName);
        $absPath = C("ABS_TMP_PATH").$tmpImgName;
        move_uploaded_file($_FILES['upload_file']['tmp_name'], $path);
        $thumbPath = C("TMP_PATH").$tmpImgName;//为了使用image，转换为相对路径
        ImageService::thumb(870, $thumbPath, $thumbPath);
        echo "<textarea><img src='{$absPath}' id='cropbox' /></textarea>";
    }
}