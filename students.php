<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Library/Library.php';
$library = new Library();

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if ($_POST['add'] == "1"){
        $library->addStudent($_POST);
    }else{
        if ($_POST['toGive'])
            $library->addOnHandBook($_POST['id'], $_POST['toGive']);
        $library->editStudent($_POST);
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
<body class="d-flex flex-column">
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
                <input class="form-control mr-sm-2" type="text" name="search" placeholder="Поиск" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Найти</button>
            </form>
        </div>
    </nav>
</header>

<!-- Begin page content -->
<main role="main" class="flex-shrink-0">
    <div class="container">
        <div class="d-flex align-items-baseline justify-content-between flex-wrap">
            <h1 class="mt-5">Ученики</h1>
            <a href="?addStudent" class="btn btn-outline-success my-2 my-sm-0">Добавить ученика</a>
        </div>
        <?
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            if (isset($_GET['search']))
                $students = $library->searchAllStudents($_GET['search']);
            elseif (isset($_GET['editStudent'])){
                $student = $library->getOneStudent($_GET['editStudent']);
                $onHandBooks = $library->getOnHandBooks($_GET['editStudent']);
                $freeBooks = $library->getFreeBooks();
            }
            elseif (isset($_GET['addStudent']))
                $addStudent = true;
            elseif (isset($_GET['backOnHandBook'])){
                $library->backOnHandBook($_GET['backOnHandBook']);
                $student = $library->getOneStudent($_GET['backStudent']);
                $onHandBooks = $library->getOnHandBooks($_GET['backStudent']);
                $freeBooks = $library->getFreeBooks();
            }
            else
                $students = $library->getAllStudents();
        }else{
            $student = $library->getOneStudent($_POST['id']);
            $onHandBooks = $library->getOnHandBooks($_POST['id']);
            $freeBooks = $library->getFreeBooks();
        }
        ?>
        <?if (isset($students)):?>
            <table class="table table-dark">
                <thead>
                <tr>
                    <th>Класс</th>
                    <th>Фамилия Имя Отчество</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="table-striped">
                <?php
                foreach ($students as $student){?>
                    <tr>
                        <td><?=$student['class']?></td>
                        <td><?=$student['fio']?></td>
                        <td><a href="?editStudent=<?=$student['id']?>">Карточка</a></td>
                    </tr>
                <?}
                ?>
                </tbody>
            </table>
        <?elseif (isset($student)):?>
            <h3>Карточка студента</h3>
            <form method="post" action="students.php">
                <input type="hidden" name="add" value="0">
                <input type="hidden" name="id" value="<?=$student['id']?>">
                <div class="row">
                    <div class="col-12 col-md-8 col-lg-10">
                        <div class="form-group">
                            <label for="fio">Фамилия Имя Отчество</label>
                            <input type="text" class="form-control" id="fio" name="fio" placeholder="Фамилия Имя Отчество" value="<?=$student['fio']?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-2">
                        <div class="form-group">
                            <label for="class">Класс</label>
                            <input type="text" class="form-control" id="class" name="class" placeholder="Класс" value="<?=$student['class']?>">
                        </div>
                    </div>
                </div>
                <h4>Выдать книгу</h4>
                <div class="form-group">
                    <label for="toGive">Если нужно выдать книгу, выберите какую</label>
                    <select class="form-control" id="toGive" name="toGive">
                        <option value="0">Не выдавать</option>
                        <?for ($i=0;$i<count($freeBooks);$i++){?>
                            <option value="<?=$freeBooks[$i]['id']?>"><?=$freeBooks[$i]['string']?></option>
                        <?}?>
                    </select>
                </div>
                <h4>Долги</h4>
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
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="table-striped">
                    <?php
                    foreach ($onHandBooks as $book){?>
                        <tr>
                            <td><?=$book['id']?></td>
                            <td><?=$book['bookcase']?></td>
                            <td><?=$book['bookshelf']?></td>
                            <td><?=$book['name']?></td>
                            <td><?=$book['authors']?></td>
                            <td><?=$book['year']?></td>
                            <td><?=$book['categorys']?></td>
                            <td><a href="?backStudent=<?=$student['id']?>&backOnHandBook=<?=$book['id']?>">Вернуть</a></td>
                        </tr>
                    <?}
                    ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Сохранить</button>
                <a href="students.php" class="btn btn-outline-success my-2 my-sm-0">Назад к списку</a>
            </form>
        <?elseif (isset($addStudent)):?>
            <h3>Добавить ученика</h3>
            <form method="post" action="students.php">
                <div class="row">
                    <input type="hidden" name="add" value="1">
                    <div class="col-12 col-md-8 col-lg-10">
                        <div class="form-group">
                            <label for="name">Фамилия Имя Отчество</label>
                            <input type="text" class="form-control" id="fio" name="fio" placeholder="Введите Фамилию Имя и Отчество">
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-2">
                        <div class="form-group">
                            <label for="year">Класс</label>
                            <input type="text" class="form-control" id="class" name="class" placeholder="Введите класс">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Добавить</button>
                <a href="students.php" class="btn btn-outline-success my-2 my-sm-0">Отмена</a>
            </form>
        <?elseif (isset($studentMessage)):?>
            <h4><?=$studentMessage?></h4>
            <a href="students.php" class="btn btn-outline-success my-2 my-sm-0">Вернуться к списку</a>
        <?else:?>
            <h3>Ученик добавлен</h3>
            <a href="students.php" class="btn btn-outline-success my-2 my-sm-0">Назад к списку</a>
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
