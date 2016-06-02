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
        $result = SearchService::searchVideo($keyWords);
        $this->assign("result", $result);
        $this->assign("keyWords", $keyWords);
        $this->display();
    }

}