<?php
session_start();

if(empty($_COOKIE['id_user'])) {
    header("Location: ../login.php");
    exit; // Выход из скрипта после перенаправления
}

require_once("../db/db.php");

// Получаем переданный id_type
$id_type = $_POST['id_type'];

// Выполняем SQL-запрос для поиска записей
$select_performers = mysqli_query($connect, "SELECT * FROM `performers` WHERE FIND_IN_SET('$id_type', `id_types_of_fault`)");

if($select_performers) {
    // Преобразуем результат в ассоциативный массив
    $performers = mysqli_fetch_all($select_performers, MYSQLI_ASSOC);
    
    // Возвращаем результат в виде JSON
    echo json_encode($performers);
} else {
    // Если произошла ошибка при выполнении запроса
    echo json_encode(array("error" => mysqli_error($connect)));
}
