<?php

namespace Home\Controller;
use Common\Service\SearchService;
use Think\Controller;

class IndexController extends Controller {

    public function index(){
        $this->display();
    }

    public function search(){
        $keyWords = I("keyWords");
        $videoResult = SearchService::searchVideo($keyWords);
        $bookResult = SearchService::searchBook($keyWords);
        $this->assign("bookResult", $bookResult);
        $this->assign("videoResult", $videoResult);
        $this->assign("keyWords", $keyWords);
        $this->display();
    }

}