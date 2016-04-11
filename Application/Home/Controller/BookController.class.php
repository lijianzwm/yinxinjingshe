<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/4/9
 * Time: 18:20
 */

namespace Home\Controller;
use Common\Service\BookService;

use Think\Controller;

class BookController extends Controller{
    public function index(){
        $this->display();
    }

    public function bookshelf(){
        $bookList = BookService::getBookList();
        $this->assign("bookList", $bookList);
        $this->display();
    }

    public function chapterList(){
        $bookId = I("id");
        $chapterList = BookService::getChapterList($bookId);
        $this->assign("bookId", $bookId);
        $this->assign("chapterList", $chapterList);
        $this->display();
    }

    public function readChapter(){
        $num = I("num");
        $bookId = I("bookId");
        $chapter = BookService::getChapter($bookId, $num);
        $maxChapterNum = BookService::getMaxChapterNum($bookId);
        $chapterIndex = BookService::getChapterIndex($bookId);
        $this->assign("chapterIndex", $chapterIndex);
        $this->assign("bookId", $bookId);
        $this->assign("maxChapterNum",$maxChapterNum);
        $this->assign("chapter", $chapter);
        $this->display();
    }



}