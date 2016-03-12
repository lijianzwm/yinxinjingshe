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
        $itemNumPerPage = 3;
        $news = M("news");
        $count = $news->count();
        $Page = new \Think\Page($count,$itemNumPerPage);
        $show = $Page->show();// 分页显示输出
        $newsList = $news->order("commit_time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('newsList', $newsList);
        $this->display();
    }

    private function getEmptyNews(){
        $news["id"] = C("NEW_NEWS");//当id为-1时，表示新插入数据
        $news['title'] = "";
        $news['author'] = "";
        $news['abstract'] = "";
        $news['content'] = "";
        $news['img_name'] = md5(uniqid(rand())).".jpg";
        return $news;
    }

    public function editNews(){
        $newsId = I("id");
        if( $newsId ){//如果id被传过来了，就是修改news
            $news = NewsService::getNews($newsId);
        }else{
            $news = $this->getEmptyNews();
        }
        $this->assign("imgPath", C("IMAGE_PATH"));//文件真正保存的路径，不包括文件名
        $this->assign("imgTmpPath", C("SEEARPHOTO_TMP_PATH"));//文件临时保存的路径，不包括文件名
        $this->assign("news", $news);
        $this->display();
    }

    public function editNewsHandler(){
        $id = I("id");
        $data['title'] = I("title");
        $data['author'] = I("author");
        $data['img_name'] = I("imgName");
        $data['abstract'] = I("abstract");
        $data['content'] = I("content");
        $imgTmpFile = I("imgTmpFile");//暂存的图片文件
        $imgData = C("IMAGE_PATH").$data['img_name'];//永久保存的图片文件
        if ($id == C("NEW_NEWS")) {//如果是插入news
            ImageService::persistence($imgTmpFile, $imgData);
            if (M("news")->add($data)) {
                $this->success("添加动态成功！", U('newsList'));
            }else{
                $this->error("添加动态失败！");
            }
        }else{//如果是修改news
            if (!ImageService::delete($imgData)) {
                $this->error("删除缓存图片失败！");
            }
            ImageService::persistence($imgTmpFile, $imgData);
            $data['id'] = $id;
            if( M("news")->save($data)){
                $this->success("修改动态成功！", U('newsList'));
            }else{
                $this->error("当前动态未被修改！");
            }
        }
    }

    public function addNewsHandler(){
        $data['title'] = I("title");
        $data['author'] = I("author");
        $data['img'] = I("img");
        $data['abstract'] = I("abstract");
        $data['content'] = I("content");
        if (M("news")->add($data)) {
            $this->success("添加动态成功！", U('newsList'));
        }else{
            $this->error("添加动态失败！");
        }
    }

    public function modifyNews(){
        $newsId = I("id");
        $news = M("news")->where("id='$newsId'")->find();
        if( $news ){
            $news['content'] = html_entity_decode($news['content']);
            $this->assign('news', $news);
            $this->display();
        }else{
            $this->error("无此信息！");
        }
    }

    public function modifyNewsHandler(){
        $data['id'] = I("id");
        $data['title'] = I("title");
        $data['author'] = I("author");
        $data['img'] = I("img");
        $data['abstract'] = I("abstract");
        $data['content'] = I("content");
        if( M("news")->save($data)){
            $this->success("修改成功！", U('newsList'));
        }else{
            $this->error("当前内容无变化！");
        }
    }

    public function deleteNews(){
        $newsId = I("newsId");
        $json = null;
        if( M("news")->where("id=$newsId")->delete()){
             $json['msg'] = true;
        }else{
            $json['msg'] = false;
        }
        echo json_encode($json);
    }
}