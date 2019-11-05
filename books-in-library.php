<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Library/Library.php';
$library = new Library();

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if ($_POST['create'] == "1"){
        $library->createBook($_POST);
    }else{
        $library->editBook($_POST);
    }
}
?>
<!doctype html>
<html lang="ru" class="h-100">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <title>Библиотека</title>
</head>
<body class="d-flex flex-column h-100">
<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="/">Библиотека</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <? require_once ($_SERVER['DOCUMENT_ROOT'].'/snippets/_navbar.php'); ?>
            </ul>
            <form class="form-inline mt-2 mt-md-0">
                <input class="form-control mr-sm-2" type="text" name="search" placeholder="Поиск" aria-label="Search" value="<?=$_GET['search']?>">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Найти</button>
            </form>
        </div>
    </nav>
</header>

<!-- Begin page content -->
<main role="main" class="flex-shrink-0">
    <div class="container">
        <div class="d-flex align-items-baseline justify-content-between flex-wrap">
            <h1 class="mt-5">Книги в библиотеке</h1>
            <a href="?addBook" class="btn btn-outline-success my-2 my-sm-0">Добавить книгу в библиотеку</a>
        </div>
        <?
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            if (isset($_GET['search']))
                $books = $library->searchAllBook($_GET['search']);
            elseif (isset($_GET['editBook'])) {
                $book = $library->getOneBook($_GET['editBook']);
                $students = $library->getAllStudents();
            }
            elseif (isset($_GET['addBook'])){
                $createBook = true;
                $bookTypes = $library->getAllTypesBook();
            }
            else
                $books = $library->getAllBook();
        }else{
            $book = $library->getOneBook($_POST['id']);
            $students = $library->getAllStudents();
            $bookMessage = "Книга \"{$book['name']}\" добавлена(изменена или выдана)";
        }
        ?>
        <?if (isset($books)):?>
            <table class="table table-dark">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Шкаф</th>
                    <th>Полка</th>
                    <th>Название</th>
                    <th>Автор</th>
                    <th>Год<br>издания</th>
                    <th>Категория</th>
                    <th>Наличие</th>
                </tr>
                </thead>
                <tbody class="table-striped">
                <?php
                foreach ($books as $book){?>
                    <tr>
                        <td><?=$book['id']?></td>
                        <td><?=$book['bookcase']?></td>
                        <td><?=$book['bookshelf']?></td>
                        <td><?=$book['name']?></td>
                        <td>
                            <? foreach ($book['authors'] as $category){?>
                                <?=$category?><br>
                            <?}?>
                        </td>
                        <td><?=$book['year']?></td>
                        <td>
                            <? foreach ($book['categorys'] as $category){?>
                                <?=$category?><br>
                            <?}?>
                        </td>
                        <?if ($book['on_hand']):?>
                            <td><a href="students.php?editStudent=<?=$book['on_hand']?>">НА РУКАХ<br>У КОГО?</a></td>
                        <?else:?>
                            <td><a href="?editBook=<?=$book['id']?>">Выдать</a></td>
                        <?endif;?>
                    </tr>
                <?}
                ?>
                </tbody>
            </table>
        <?elseif (isset($book)):?>
            <h3>Перемещение книги</h3>
            <form method="post" action="books-in-library.php">
                <input type="hidden" name="create" value="0">
                <input type="hidden" name="id" value="<?=$book['id']?>">
                <div class="row">
                    <div class="col-12"><h6><?=$book['name']?><br><?=$book['id']?> | <?=$book['authors']?> | <?=$book['year']?> | <?=$book['categorys']?></h6></div>
                    <div class="col-6 col-md-2">
                        <div class="form-group">
                            <label for="bookcase">Шкаф</label>
                            <select class="form-control" id="bookcase" name="bookcase">
                                <?for ($i=1; $i<21; $i++){?>
                                    <?if ($i == $book['bookcase']):?>
                                        <option value="<?=$i?>" selected><?=$i?></option>
                                    <?else:?>
                                        <option value="<?=$i?>"><?=$i?></option>
                                    <?endif;?>
                                <?}?>
                            </select>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="form-group">
                            <label for="bookshelf">Полка</label>
                            <select class="form-control" id="bookshelf" name="bookshelf">
                                <?for ($i=1; $i<16; $i++){?>
                                    <?if ($i == $book['bookshelf']):?>
                                        <option value="<?=$i?>" selected><?=$i?></option>
                                    <?else:?>
                                        <option value="<?=$i?>"><?=$i?></option>
                                    <?endif;?>
                                <?}?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="onHandStudent">Выдать ученику</label>
                            <select class="form-control" id="onHandStudent" name="onHandStudent">
                                <option value="0">Не выдавать книгу</option>
                                <?foreach ($students as $student){?>
                                    <?if ($book['on_hand'] == $student['id']):?>
                                        <option value="<?=$student['id']?>" selected><?=$student['fio']?> | <?=$student['class']?></option>
                                    <?else:?>
                                        <option value="<?=$student['id']?>"><?=$student['fio']?> | <?=$student['class']?></option>
                                    <?endif;?>
                                <?}?>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Сохранить</button>
                <a href="books-in-library.php" class="btn btn-outline-success my-2 my-sm-0">Отмена</a>
            </form>
            <h4><?=$bookMessage?></h4>
        <?elseif (isset($createBook)):?>
            <h3>Добавление книги в библиотеку из типов книг</h3>
            <form method="post" action="books-in-library.php">
                <input type="hidden" name="create" value="1">
                <div class="row">
                    <div class="col-12 col-md-10">
                        <div class="form-group">
                            <label for="createBook">Выберите тип книги</label>
                            <select class="form-control" id="createBook" name="createBook">
                                <?foreach ($bookTypes as $bookType) {?>
                                    <option value="<?=$bookType['id']?>"><?=$bookType['name']?> | <?=implode(' ', $bookType['authors'])?> | <?=$bookType['year']?></option>
                                <?}?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-2">
                        <div class="form-group">
                            <label for="quan">Колличество</label>
                            <input type="number" class="form-control" id="quan" name="quan" placeholder="Колличество" min="1" max="10" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Создать</button>
                <a href="books-in-library.php" class="btn btn-outline-success my-2 my-sm-0">Отмена</a>
            </form>
        <?elseif (isset($bookMessage)):?>
            <h4><?=$bookMessage?></h4>
            <a href="books-in-library.php" class="btn btn-outline-success my-2 my-sm-0">Вернуться к списку</a>
        <?else:?>
            <h3>Книг не найдено</h3>
        <?endif;?>
    </div>
</main>

<? require_once ($_SERVER['DOCUMENT_ROOT'].'/snippets/_footer.php'); ?>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
