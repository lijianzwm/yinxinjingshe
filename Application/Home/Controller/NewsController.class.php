<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/9
 * Time: 19:10
 */

namespace Home\Controller;


use Think\Controller;
use \Common\Service;

class NewsController extends Controller{
    public function readNews(){
        $service = new Service\NewsService();
        $news = $service->getNews(I("id"));
        $this->assign("news", $news);
        $this->display();
    }
}