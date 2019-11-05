<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Library/Library.php';
$library = new Library();

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if ($_POST['add'] == "1"){
        $message = $library->addCategory($_POST);
        $addCategory = true;
        if ($message)
            $message = "Категория \"$message\" уже существует!";
        else
            $message = "Категория создана!";
    }else{
        print_r($_POST);
        $library->editCategory($_POST);
        $category['id'] = $_POST['id'];
        $category['category'] = $_POST['category_name'];
        $message = "Название категории изменено на {$category['category']}";
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
            <form class="form-inline mt-2 mt-md-0" method="get">
                <input class="form-control mr-sm-2" type="text" placeholder="Поиск" aria-label="Search" name="search" value="<?=$_GET['search']?>">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Найти</button>
            </form>
        </div>
    </nav>
</header>

<!-- Begin page content -->
<main role="main" class="flex-shrink-0">
    <div class="container">
        <div class="d-flex align-items-baseline justify-content-between flex-wrap">
            <h1 class="mt-5">Категории книг</h1>
            <a href="<?=$_SERVER['PHP_SELF']?>?add" class="btn btn-outline-success my-2 my-sm-0" type="submit">Добавить категорию</a>
        </div>


        <?
        if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if(isset($_GET['search'])){
                $categorys = $library->searchAllCategorys($_GET['search']);
            }elseif(isset($_GET['add'])){
                $addCategory = true;
            }elseif (isset($_GET['editCategory'])){
    ;           $category = $library->getOneCategory($_GET['editCategory']);
            }else{
                $categorys = $library->getAllCategorys();
            }
        }
        ?>
        <?if (isset($categorys)):?>
            <table class="table table-dark">
                <thead>
                <tr>
                    <th>Категория</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="table-striped">
                <?php
                foreach ($categorys as $category){?>
                    <tr>
                        <td><?=$category['category']?></td>
                        <td><a href="?editCategory=<?=$category['id']?>">Рeд.</a></td>
                    </tr>
                <?}
                ?>
                </tbody>
            </table>
        <?elseif ($addCategory):?>
            <h3>Добавить категорию</h3>
            <form method="post" action="category.php">
                <div class="row">
                    <input type="hidden" name="add" value="1">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="category_name">Название категории</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Введите Название категории">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Добавить</button>
                <a href="category.php" class="btn btn-outline-success my-2 my-sm-0">Отмена</a>
            </form>
            <h4><?=$message?></h4>
        <?elseif ($category):?>
            <h3>Изменить категорию</h3>
            <form method="post" action="category.php">
                <div class="row">
                    <input type="hidden" name="add" value="0">
                    <input type="hidden" name="id" value="<?=$category['id']?>">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="category_name">Название категории</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Введите Название категории" value="<?=$category['category']?>">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Изменить</button>
                <a href="category.php" class="btn btn-outline-success my-2 my-sm-0">Назад</a>
            </form>
            <h4><?=$message?></h4>
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
