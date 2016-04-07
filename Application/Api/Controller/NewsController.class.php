<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/9
 * Time: 19:38
 */

namespace Api\Controller;


use Think\Controller;
use \Common\Service;

class NewsController extends Controller{

    public function renderList(){
        $limit = C("NEWS_PER_PAGE");
        $page = I("page");
        $service = new \Common\Service\NewsService();
        echo json_encode($service->renderNewsList($page, $limit));
    }

    public function readNews(){
        $service = new \Common\Service\NewsService();
        $news = $service->getNews(I("id"));
        echo json_encode($news);
    }

}