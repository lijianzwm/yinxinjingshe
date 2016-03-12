<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/3/9
 * Time: 19:11
 */

namespace Common\Service;


class NewsService{
    public function renderNewsList( $page, $limit ){
        return M("news")->order("commit_time desc")->page($page,$limit)->select();
    }

    public function getNews( $id ){
        return M("news")->where("id='$id'")->find();
    }

}