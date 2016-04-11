<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/4/11
 * Time: 9:15
 */

namespace Api\Controller;


use Think\Controller;
use Common\Service\BookService;

class BookController extends Controller{

    /**
     * @param $bookId
     * @param $chapterNum
     */
    public function renderChapter(){
        $chapter = BookService::getChapter(I("bookId"), I("chapterNum"));
        if( $chapter ){
            $json['error_code'] = 0;
            $json['title'] = $chapter['title'];
            $json['num'] = $chapter['num'];
            $json['bookId'] = $chapter['book_id'];
            $json['content'] = $chapter['content'];
        }else{
            $json['error_code'] = 1;
            $json['msg'] = "mysql data not found";
        }
        echo json_encode($json);
    }
}