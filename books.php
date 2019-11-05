<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Library/Library.php';
$library = new Library();

if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if ($_POST['create'] == "1"){
        $library->createTypeBook($_POST);
    }else{
        $library->editTypeBook($_POST);
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
            <h1 class="mt-5">Книги</h1>
            <a href="?addType" class="btn btn-outline-success my-2 my-sm-0">Создать книгу</a>
        </div>
        <?
            if ($_SERVER['REQUEST_METHOD'] == 'GET')
            {
                if (isset($_GET['search']))
                    $books = $library->getSearchTypeBook($_GET['search']);
                elseif (isset($_GET['editType']))
                    $book = $library->getOneTypeBook($_GET['editType']);
                elseif (isset($_GET['addType']))
                    $createType = true;
                else
                    $books = $library->getAllTypesBook();
            }else{
                $book = $library->getOneTypeBook($_POST['id']);
                $bookMessage = "Книга {$_POST['name']} добавлена(изменена)";
            }
        ?>
        <?if (isset($books)):?>
        <table class="table table-dark">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Автор</th>
                    <th>Год<br>издания</th>
                    <th>Категория</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="table-striped">
            <?php
                foreach ($books as $book){?>
                    <tr>
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
                        <td><a href="?editType=<?=$book['id']?>">Ред.</a></td>
                    </tr>
                <?}
            ?>
            </tbody>
        </table>
        <?elseif (isset($book)):?>
        <h3>Редактирование книги</h3>
        <form method="post" action="books.php">
            <input type="hidden" name="create" value="0">
            <input type="hidden" name="id" value="<?=$book['id']?>">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-10">
                    <div class="form-group">
                        <label for="name">Название книги</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Введите название книги" value="<?=$book['name']?>">
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-2">
                    <div class="form-group">
                        <label for="year">Год</label>
                        <input type="number" class="form-control" id="year" name="year" placeholder="Введите год издания" value="<?=$book['year']?>" min="1800" max="2100">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="categorys">Категория</label>
                <select multiple class="form-control" id="categorys" name="categorys[]">
                    <?for ($i=0;$i<count($library->categorys);$i++){?>
                        <option value="<?=$library->categorys[$i]['category']?>"<?if (array_search($library->categorys[$i]['category'], $book['categorys']) !== false):?> selected<?endif;?>><?=$library->categorys[$i]['category']?></option>
                    <?}?>
                </select>
            </div>
            <div class="form-group">
                <label for="authors">Автор</label>
                <select multiple class="form-control" id="authors" name="authors[]">
                    <?for ($i=0;$i<count($library->authors);$i++){?>
                        <option value="<?=$library->authors[$i]['a_fio']?>"<?if (array_search($library->authors[$i]['a_fio'], $book['authors']) !== false):?> selected<?endif;?>><?=$library->authors[$i]['a_fio']?></option>
                    <?}?>
                </select>
            </div>
            <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Изменить</button>
            <a href="books.php" class="btn btn-outline-success my-2 my-sm-0">Отмена</a>
        </form>
        <h4><?=$bookMessage?></h4>
        <?elseif (isset($createType)):?>
            <h3>Создать книгу</h3>
            <form method="post" action="books.php">
                <input type="hidden" name="create" value="1">
                <div class="row">
                    <div class="col-12 col-md-8 col-lg-10">
                        <div class="form-group">
                            <label for="name">Название книги</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Введите название книги">
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-2">
                        <div class="form-group">
                            <label for="year">Год</label>
                            <input type="number" class="form-control" id="year" name="year" placeholder="Введите год издания" min="1800" max="2100">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="categorys">Категория</label>
                    <select multiple class="form-control" id="categorys" name="categorys[]">
                        <?for ($i=0;$i<count($library->categorys);$i++){?>
                            <option value="<?=$library->categorys[$i]['category']?>"><?=$library->categorys[$i]['category']?></option>
                        <?}?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="authors">Автор</label>
                    <select multiple class="form-control" id="authors" name="authors[]">
                        <?for ($i=0;$i<count($library->authors);$i++){?>
                            <option value="<?=$library->authors[$i]['a_fio']?>"><?=$library->authors[$i]['a_fio']?></option>
                        <?}?>
                    </select>
                </div>
                <button type="submit" class="btn btn-outline-success my-2 my-sm-0">Создать</button>
                <a href="books.php" class="btn btn-outline-success my-2 my-sm-0">Отмена</a>
            </form>
        <?elseif (isset($bookMessage)):?>
        <h4><?=$bookMessage?></h4>
            <a href="books.php" class="btn btn-outline-success my-2 my-sm-0">Вернуться к списку</a>
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
