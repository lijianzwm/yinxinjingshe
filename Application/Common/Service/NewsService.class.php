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
        $newsList = M("news")->order("create_time desc")->page($page,$limit)->select();
        foreach ($newsList as $item) {
            $item['img_name'] = C("ABS_IMAGE_PATH").$item['img_name'];
        }
        return $newsList;
    }

    public static function getNews( $id ){
        $news = M("news")->where("id='$id'")->find();
        $news['content'] = html_entity_decode($news['content']);
        return $news;
    }

}