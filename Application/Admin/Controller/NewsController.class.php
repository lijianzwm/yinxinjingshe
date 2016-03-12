<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/8
 * Time: 9:05
 */

namespace Admin\Controller;


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

    public function addNews(){
        $news['title'] = "";
        $news['author'] = "";
        $news['img'] =  "";
        $news['abstract'] = "";
        $news['content'] = "";
        $news['imgName'] = md5(uniqid(rand())).".jpg";
        $this->display();
    }

    public function editNews(){
        $news['title'] = "";
        $news['author'] = "";
        $news['img'] =  "";
        $news['abstract'] = "";
        $news['content'] = "";
        $news['imgName'] = md5(uniqid(rand())).".jpg";
        $imgUrl = C('NEWS_IMG_PATH');
        $this->assign("imgUrl", $imgUrl);
        $this->assign("news", $news);
        $this->display();
    }

    public function editNewsHandler(){
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