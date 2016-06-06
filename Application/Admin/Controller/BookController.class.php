<?php
/**
 * Created by PhpStorm.
 * User: lijian
 * Date: 2016/4/10
 * Time: 13:30
 */

namespace Admin\Controller;
use Common\Service\BookService;

class BookController extends CommonController{
    public function bookList(){
        $bookList = BookService::getBookList();
        $this->assign("bookList", $bookList);
        $this->display();
    }

    public function addBook(){
        $book = BookService::getEmptyBook();
        $this->assign("book", $book);
        $this->display("editBook");
    }

    public function modifyBook(){
        $book = BookService::getBookInfo( I("id") );
        $this->assign("book", $book);
        $this->display("editBook");
    }

    public function deleteBook(){
        //TODO 删除图书
    }

    public function editBookHandler(){
        $id = I("id");
        $book['name'] = I("name");
        $book['author'] = I("author");
        $book['num'] = I("num");
        if( -1 == $id ){//如果是添加书籍
            if( M("book")->add($book) ){
                $this->success("添加书籍成功！", U('bookList'));
            }else{
                $this->error("添加书籍失败，数据库写入错误！");
            }
        }else{
            $book['id'] = $id;
            if( M("book")->save($book) ){
                $this->success("修改信息成功！");
            }else{
                $this->success("当前书籍信息未被修改！");
            }
        }
    }

    public function chapterList(){
        $chapterList = BookService::getChapterList(I("id"));
        $this->assign("bookId", I("id"));
        $this->assign("bookName", I("bookName"));
        $this->assign("chapterList", $chapterList);
        $this->display();
    }

    public function addChapter(){
        $chapter = BookService::getEmptyChapter(I("bookId"));
        $this->assign("bookName", I("bookName"));
        $this->assign("bookId", I("bookId"));
        $this->assign("chapter", $chapter);
        $this->display("editChapter");
    }

    public function modifyChapter(){
        $chapter = BookService::getChapterById( I("id") );
        $this->assign("bookName", I("bookName"));
        $this->assign("bookId", I("bookId"));
        $this->assign("chapter", $chapter);
        $this->display("editChapter");
    }

    public function editChapterHandler(){
        $id = I("id");
        $chapter['title'] = I("title");
        $chapter['num'] = I("num");
        $chapter['content'] = I("content");
        $chapter['book_id'] = I("bookId");
        if( -1 == $id ){//添加章节
            if (M("chapter")->add($chapter)) {
                $this->success("添加章节成功！", U('chapterList', array('id'=>I("bookId"))));
            }else{
                $this->error("添加章节失败，写入数据库错误！");
            }
        }else{//修改章节
            $chapter['id'] = $id;
            if (M("chapter")->save($chapter)) {
                $this->success("修改章节成功！", U('chapterList', array('id'=>I("bookId"))));
            }else{
                $this->success("章节数据未被修改！");
            }
        }

    }

    public function deleteChapter(){
        //TODO 删除章节
    }

}
