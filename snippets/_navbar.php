<?php
$menuItems=[
    ["/books.php", "Книги"],
    ["/books-in-library.php", "Книги в библиотеке"],
    ["/students.php", "Ученики"],
    ["/authors.php", "Авторы"],
    ["/category.php", "Категории"],
    ];
foreach ($menuItems as $item){?>
    <li class="nav-item<?=$item[0]==$_SERVER['PHP_SELF'] ? " active" : ""?>">
        <a class="nav-link" href="<?=$item[0]?>"><?=$item[1]?></a>
    </li>
<?php
};