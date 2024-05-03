<?php

session_start();

if(empty($_COOKIE['id_user'])) {
    header("Location: ./login.php");
}

require_once("./db/db.php");

$performer_id = $_GET['id'];

$select_performer = mysqli_query($connect, "SELECT * FROM `performers` WHERE `id`='$performer_id'");
$select_performer = mysqli_fetch_assoc($select_performer);

// Получаем значения из поля id_types_of_fault
$id_types_of_fault = explode(',', $select_performer['id_types_of_fault']);

// Преобразуем каждое значение в целое число
$id_types_of_fault = array_map('intval', $id_types_of_fault);

// Формируем строку с id для использования в SQL-запросе
$id_types_of_fault_str = implode(',', $id_types_of_fault);

// Выполняем запрос к таблице type_of_fault
$select_type_of_fault = mysqli_query($connect, "SELECT * FROM `type_of_fault` WHERE `id` IN ($id_types_of_fault_str)");

$type_of_fault = array();
// Если запрос успешен, преобразуем результат в ассоциативный массив
if($select_type_of_fault) {
    $type_of_fault = mysqli_fetch_all($select_type_of_fault, MYSQLI_ASSOC);
} else {
    // Если произошла ошибка при выполнении запроса, выводим сообщение об ошибке
    echo "Ошибка при выполнении запроса: " . mysqli_error($connect);
}

// Выводим информацию о исполнителе и типах неисправностей
var_dump($type_of_fault);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $select_performer['name_performer'] ?></title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <a href="./index.php">Назад</a>

    <h1>Информация о исполнителе</h1>

    <h2>Типы неисправностей:</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Дополнительные услуги</th>
        </tr>
        <?php foreach ($type_of_fault as $fault): ?>
            <tr>
                <td><?= $fault['id'] ?></td>
                <td><?= $fault['name_type'] ?></td>
                <td>
                    <?php 
                    // Преобразовываем строку с JSON в ассоциативный массив
                    $additional_services = json_decode($fault['additional_services'], true);
                    // Перебираем дополнительные услуги и выводим их
                    foreach ($additional_services['additional_services'] as $service) {
                        echo $service['name'] . ': ' . $service['price'] . ' руб.<br>';
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>