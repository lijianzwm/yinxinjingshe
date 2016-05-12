<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/8
 * Time: 9:05
 */

namespace Admin\Controller;
use Common\Service\NewsService;
use Common\Service\ImageService;

class NewsController extends CommonController{
    public function newsList(){
        $itemNumPerPage = C("NEWS_PER_PAGE");
        $news = M("news");
        $count = $news->count();
        $Page = new \Think\Page($count,$itemNumPerPage);
        $show = $Page->show();// 分页显示输出
        $newsList = $news->order("create_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('newsList', $newsList);
        $this->display();
    }

    public function editNews(){
        $newsId = I("id");
        if( $newsId ){//如果id被传过来了，就是修改news
            $news = NewsService::getNews($newsId);
            $imgName = $news['img_name'];
            $this->assign("imgInit", C("ABS_IMAGE_PATH").$news['img_name']);
        }else{
            $news = NewsService::getEmptyNews();
            $imgName = md5(uniqid(rand())).".jpg";
            $this->assign("imgInit",C("IMG_NO_PIC"));
        }
        $this->assign("imgViewPath", C("ABS_IMAGE_PATH").$imgName);
        $this->assign("tmpImgName", "tmp_".$imgName);//上传完整图的名称
        $this->assign("imgName", $imgName);//图片截取后的名称
        $this->assign("news", $news);
        $this->display();
    }

    public function editNewsHandler(){
        $id = I("id");
        $data['title'] = I("title");
        $data['author'] = I("author");
        $data['img_name'] = I("img");
        $data['abstract'] = I("abstract");
        $data['content'] = I("content");
        $data['update_time'] = date("Y-m-d H:i:s");
        if ($id == C("NEW_NEWS")) {//如果是插入news
            $data['create_time'] = date("Y-m-d H:i:s");
            if (M("news")->add($data)) {
                $this->success("添加推送成功！", U('newsList'));
            }else{
                $this->error("添加推送失败！");
            }
        }else{//如果是修改news
            $data['id'] = $id;
            if( M("news")->save($data)){
                $this->success("修改推送成功！", U('newsList'));
            }else{
                //TODO 添加判断图片是否被修改的代码
                $this->success("修改推送成功！", U('newsList'));
            }
        }
    }

    public function deleteNews(){
        $newsId = I("newsId");
        $news = M("news")->where("id=$newsId")->find();
        $json = null;
        if( M("news")->where("id=$newsId")->delete()){
             unlink(C("IMAGE_PATH").$news['img_name'] );
             $json['msg'] = true;
             $json['hint'] = "删除成功！";
        }else{
            $json['msg'] = false;
            $json['hint'] = "删除失败！";
        }
        echo json_encode($json);
    }

    public function stickieNews(){
        $newsId = I("newsId");
        $json = null;
        $news = M("news")->where("id=$newsId")->find();
        if( $news ){
            if( $news["create_time"] == C("STICKIE_TIME")){
                $json['msg'] = false;
                $json['hint'] = "该推送已经被置顶了，不能重复置顶！";
            }else{
                $news["create_time"] = C("STICKIE_TIME");
                if (M("news")->save($news)) {
                    $json['msg'] = true;
                    $json['hint'] = "置顶成功！";
                }else{
                    $json['msg'] = false;
                    $json['hint'] = "置顶失败！";
                }
            }
        }else{
            $json['msg'] = false;
            $json['hint'] = "置顶失败！";
        }
        echo json_encode($json);
    }

    public function unstickieNews(){
        $newsId = I("newsId");
        $json = null;
        $news = M("news")->where("id=$newsId")->find();
        if( $news && $news['create_time'] == C("STICKIE_TIME")){
            $news['create_time'] = $news["update_time"];
            if (M("news")->save($news)) {
                $json['msg'] = true;
                $json['hint'] = "取消置顶成功！";
            }else{
                $json['msg'] = false;
                $json['hint'] = "取消置顶失败！";
            }
        }else{
            $json['msg'] = false;
            $json['hint'] = "该推送未被置顶！";
        }
        echo json_encode($json);
    }

}