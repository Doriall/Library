<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Library/safemysql.class.php';

class Library extends SafeMySQL
{

    private $table_books = 'books';
    private $table_books_type = 'books_type';
    private $table_authors = 'authors';
    private $table_categorys = 'categorys';
    private $table_students = 'students';

    public $bookAll;
    public $bookOnHand;
    public $bookInLibrary;

    public $categorys;
    public $authors;


    function __construct()
    {
        parent::__construct();
        $this->bookAll = $this->getOne('SELECT COUNT(*) FROM ?n', $this->table_books);
        $this->bookOnHand = $this->getOne('SELECT COUNT(*) FROM ?n WHERE on_hand!=0', $this->table_books);
        $this->bookInLibrary = $this->bookAll - $this->bookOnHand;
        $this->authors = $this->getAll('SELECT * FROM ?n',$this->table_authors);
        $this->categorys = $this->getAll('SELECT * FROM ?n',$this->table_categorys);
    }
    function getAllTypesBook(){
        $books = $this->getAll('SELECT * FROM ?n', $this->table_books_type);
        foreach ($books as $index => $book){
            $books[$index]['authors'] = explode(";;", $book['authors']);
            $books[$index]['categorys'] = explode(";;", $book['categorys']);
        }
        return $books;
    }
    function getSearchTypeBook($search_word){
        $books = $this->getAll('SELECT * FROM ?n WHERE 
                                authors LIKE ?s OR year LIKE ?s OR categorys LIKE ?s OR name LIKE ?s',
            $this->table_books_type,
            '%'.$search_word.'%', '%'.$search_word.'%', '%'.$search_word.'%', '%'.$search_word.'%');
        foreach ($books as $index => $book){
            $books[$index]['authors'] = explode(";;", $book['authors']);
            $books[$index]['categorys'] = explode(";;", $book['categorys']);
        }
        return $books;
    }
    function getOneTypeBook($id){
        $book = $this->getRow('SELECT * FROM ?n t1 WHERE t1.id LIKE ?i',
            $this->table_books_type, $id);
        $book['authors'] = explode(";;", $book['authors']);
        $book['categorys'] = explode(";;", $book['categorys']);
        return $book;
    }
    function editTypeBook($post){
        $post['authors'] = implode(";;", $_POST['authors']);
        $post['categorys'] = implode(";;", $_POST['categorys']);
        $this->query('UPDATE ?n SET name = ?s,authors = ?s,categorys = ?s, year = ?i WHERE id=?i',
            $this->table_books_type, $post['name'], $post['authors'], $post['categorys'], $post['year'], $post['id']);
    }
    function createTypeBook($post){
        $post['authors'] = implode(";;", $_POST['authors']);
        $post['categorys'] = implode(";;", $_POST['categorys']);
        $this->query('INSERT INTO ?n (name, authors, categorys, year) 
                                            VALUES (?s, ?s, ?s, ?s)',
            $this->table_books_type, $post['name'], $post['authors'], $post['categorys'], $post['year']);
    }
    function getAllBook()
    {
        $books = $this->getAll('SELECT t1.id, t1.bookcase, t1.bookshelf, t1.on_hand, t2.name, t2.authors, t2.categorys, t2.year
                                FROM ?n t1 LEFT JOIN ?n t2 ON t1.id_books_type = t2.id',
            $this->table_books, $this->table_books_type);
        foreach ($books as $index => $book){
            $books[$index]['authors'] = explode(";;", $book['authors']);
            $books[$index]['categorys'] = explode(";;", $book['categorys']);
        }
        return $books;
    }
    function getOneBook($id)
    {
        $book = $this->getRow('SELECT t1.id, t1.bookcase, t1.bookshelf, t1.on_hand, t2.name, t2.authors, t2.categorys, t2.year, t3.id AS id_book, t3.fio, t3.class
                                FROM ?n t1 LEFT JOIN ?n t2 ON t1.id_books_type = t2.id
                                           LEFT JOIN ?n t3 ON t1.on_hand = t3.id WHERE t1.id = ?i',
            $this->table_books, $this->table_books_type, $this->table_students, $id);
        $book['authors'] = str_replace(";;", ", ", $book['authors']);
        $book['categorys'] = str_replace(";;", ", ", $book['categorys']);

        return $book;
    }
    function searchAllBook($search_word)
    {
        $books = $this->getAll('SELECT t1.id, t1.bookcase, t1.bookshelf, t1.on_hand, t2.name, t2.authors, t2.categorys, t2.year
                                FROM ?n t1 LEFT JOIN ?n t2 ON t1.id_books_type = t2.id
                                WHERE t2.authors LIKE ?s OR t2.year LIKE ?s OR t2.categorys LIKE ?s OR t2.name LIKE ?s',
            $this->table_books, $this->table_books_type,
            '%'.$search_word.'%', '%'.$search_word.'%', '%'.$search_word.'%', '%'.$search_word.'%');
        foreach ($books as $index => $book){
            $books[$index]['authors'] = explode(";;", $book['authors']);
            $books[$index]['categorys'] = explode(";;", $book['categorys']);
        }
        return $books;
    }
    function editBook($post){
        $this->query('UPDATE ?n SET bookcase = ?i,bookshelf = ?i,on_hand = ?i WHERE id = ?i',
                        $this->table_books, $post['bookcase'], $post['bookshelf'], $post['onHandStudent'], $post['id']);
    }
    function createBook($post){
        for ($i=0;$i<$post['quan'];$i++) {
            $this->query('INSERT INTO ?n (bookcase, bookshelf, id_books_type, on_hand) VALUES (1, 1, ?i, 0)',
                $this->table_books, $post['createBook']);
        }
    }
    function getOnHandBooks($id)
    {
        $books = $this->getAll('SELECT t1.id, t1.bookcase, t1.bookshelf, t2.name, t2.authors, t2.year, t2.categorys
                                FROM ?n t1 LEFT JOIN ?n t2 ON t1.id_books_type = t2.id
                                WHERE t1.on_hand = ?s',
            $this->table_books, $this->table_books_type, $id);
        foreach ($books as $index => $book){
            $books[$index]['authors'] = str_replace(";;", "<br>", $book['authors']);
            $books[$index]['categorys'] = str_replace(";;", "<br>", $book['categorys']);
        }
        return $books;
    }
    function addOnHandBook($id_student, $id_book){
        $this->query('UPDATE ?n SET on_hand=?i WHERE id=?i',
            $this->table_books, $id_student, $id_book);
    }
    function backOnHandBook($id_book){
        $this->query('UPDATE ?n SET on_hand=0 WHERE id=?i',
            $this->table_books, $id_book);
    }
    function getFreeBooks()
    {
        $books = $this->getAll('SELECT t1.id, t2.name, t2.authors, t2.year
                                FROM ?n t1 LEFT JOIN ?n t2 ON t1.id_books_type = t2.id
                                WHERE t1.on_hand = 0',
            $this->table_books, $this->table_books_type);
        foreach ($books as $index => $book){
            $books[$index]['authors'] = str_replace(";;", ", ", $book['authors']);
            $books[$index]['categorys'] = str_replace(";;", ", ", $book['categorys']);
            $books[$index]['string'] = "#{$book['id']} | {$book['name']} | {$books[$index]['authors']} | {$book['year']}";
        }
        return $books;
    }

    function getAllStudents(){
        $students = $this->getAll('SELECT * FROM ?n', $this->table_students);
        return $students;
    }
    function getOneStudent($id){
        $student = $this->getRow('SELECT * FROM ?n WHERE id = ?i', $this->table_students, $id);
        return $student;
    }
    function searchAllStudents($search_word){
        $books = $this->getAll('SELECT * FROM ?n WHERE class LIKE ?s OR fio LIKE ?s',
                    $this->table_students, '%'.$search_word.'%', '%'.$search_word.'%');
        return $books;
    }
    function addStudent($post){
        $this->query('INSERT INTO ?n (fio, class) 
                  VALUES (?s, ?s)', $this->table_students, $post['fio'], $post['class']);
        //return $books;
    }
    function editStudent($post){
        $this->query('UPDATE ?n SET fio=?s, class=?s WHERE id=?i'
                        , $this->table_students, $post['fio'], $post['class'], $post['id']);
        //return $books;
    }
    function getAllCategorys(){
        return $this->getAll('SELECT * FROM ?n', $this->table_categorys);
    }
    function searchAllCategorys($search_word){
        return $this->getAll('SELECT * FROM ?n  WHERE category LIKE ?s', $this->table_categorys, '%'.$search_word.'%');
    }
    function getOneCategory($id){
        return $this->getRow('SELECT * FROM ?n  WHERE id = ?i', $this->table_categorys, $id);
    }
    function addCategory($post){
        $result = $this->getOne('SELECT category FROM ?n  WHERE category = ?s', $this->table_categorys, $post['category_name']);
        if ($result)
            return $result;
        else{
            $this->query('INSERT INTO ?n (category) VALUES (?s)', $this->table_categorys, $post['category_name']);
            return 0;
        }
    }
    function editCategory($post){
        $this->query('UPDATE ?n SET category=?s WHERE id=?i'
            , $this->table_categorys, $post['category_name'], $post['id']);
    }

    function getAllAuthors(){
        return $this->getAll('SELECT * FROM ?n', $this->table_authors);
    }
    function searchAllAuthors($search_word){
        return $this->getAll('SELECT * FROM ?n  WHERE a_fio LIKE ?s', $this->table_authors, '%'.$search_word.'%');
    }
    function getOneAuthor($id){
        return $this->getRow('SELECT * FROM ?n  WHERE id = ?s', $this->table_authors, $id);
    }
    function addAuthor($post){
        $result = $this->getOne('SELECT a_fio FROM ?n  WHERE a_fio = ?s', $this->table_authors, $post['author_name']);
        if ($result)
            return $result;
        else{
            $this->query('INSERT INTO ?n (a_fio) VALUES (?s)', $this->table_authors, $post['author_name']);
            return 0;
        }
    }
    function editAuthor($post){
        $this->query('UPDATE ?n SET a_fio=?s WHERE id=?i'
            , $this->table_authors, $post['author_name'], $post['id']);
    }
}