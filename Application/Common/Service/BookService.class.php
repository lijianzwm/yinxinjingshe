<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/4/10
 * Time: 11:50
 */

namespace Common\Service;


class BookService{

    public static function getEmptyChapter($bookId){
        $chapter['id'] = -1;
        $chapter['book_id'] = $bookId;
        $chapters = M("chapter")->order("num desc")->select();
        if( $chapters ){
            $chapter['num'] = intval($chapters[0]['num'] )+1;
        }else{
            $chapter['num'] = 1;
        }
        return $chapter;
    }

    public static function getChapter($bookId, $num){
        $chapter = M("chapter")->where("book_id = '$bookId' and num = '$num'")->find();
        $chapter['content'] = html_entity_decode($chapter['content']);
        return $chapter;
    }

    public static function getChapterById($chapterId){
        $chapter = M("chapter")->where("id=$chapterId")->find();
        $chapter['content'] = html_entity_decode($chapter['content']);
        return $chapter;
    }

    public static function getChapterList($bookId){
        return M("chapter")->order("num")->where("book_id=$bookId")->select();
    }

    public static function getMaxChapterNum($bookId){
        return M("chapter")->where("book_id=$bookId")->count();
    }

    public static function updateIndex($bookId){
        //TODO 刷新书籍目录
    }

    public static function getBookInfo($bookId){
        return M("book")->where("id=$bookId")->find();
    }

    public static function getIndex($book){
        return json_decode($book['index']);
    }

    public static function getBookList(){
        return M("book")->order("num")->select();
    }

    public static function getEmptyBook(){
        $book['id'] = -1;
        return $book;
    }

}