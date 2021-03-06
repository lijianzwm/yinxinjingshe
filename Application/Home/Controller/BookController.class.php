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
        layout(false);
        $bookId = I("id");
        $chapterList = BookService::getChapterList($bookId);
        $this->assign("bookId", $bookId);
        $this->assign("chapterList", $chapterList);
        $this->display();
    }

    public function readChapter(){
        layout(false);
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

    public function readSearchChapter(){
        layout(false);
        $num = I("num");
        $bookId = I("bookId");
        $keyWords = I("keyWords");
        $chapter = BookService::getChapter($bookId, $num);
        $chapter['content'] = html_entity_decode($chapter['content']);
        $chapter['content'] = str_replace($keyWords, "<strong style=\"color:red\">$keyWords</strong>", $chapter['content']);
        $maxChapterNum = BookService::getMaxChapterNum($bookId);
        $chapterIndex = BookService::getChapterIndex($bookId);
        $this->assign("chapterIndex", $chapterIndex);
        $this->assign("bookId", $bookId);
        $this->assign("maxChapterNum",$maxChapterNum);
        $this->assign("chapter", $chapter);
        $this->display("readChapter");
    }

}