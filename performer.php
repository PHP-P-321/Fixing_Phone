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
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 300px;
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

    <h2>Оставить заявку на ремонт</h2>
    <form action="./vendor/create_request.php" method="post">
        <input type="hidden" name="id_performer" value="<?= $performer_id ?>">
        <input type="hidden" name="id_client" value="<?= $_COOKIE['id_user']; ?>">
        <input type="text" name="name_phone" placeholder="Название телефона" required>
        <input type="text" name="model_phone" placeholder="Название модели" required>
        <input type="text" name="serial_number" placeholder="Серийный номер" required>

        <div class="services">
            <h3>Типы неисправностей:</h3>
            <ul>
                <?php foreach ($type_of_fault as $fault): ?>
                    <li>
                        <input type="checkbox" name="fault_type[]" id="fault_type<?= $fault['id'] ?>" value="<?= $fault['id'] ?>">
                        <label for="fault_type<?= $fault['id'] ?>"><?= $fault['name_type'] ?></label>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php foreach ($type_of_fault as $fault): ?>
            <div id="additional_services_<?= $fault['id'] ?>" class="additional_services" style="display: none;">
                <h3>Дополнительные услуги для <?= $fault['name_type'] ?>:</h3>
                <ul>
                    <?php 
                    // Преобразовываем строку с JSON в ассоциативный массив
                    $additional_services = json_decode($fault['additional_services'], true);
                    ?>
                    <?php foreach ($additional_services['additional_services'] as $service): ?>
                        <li>
                            <input type="radio" name="additional_service_<?= $fault['id'] ?>" id="additional_service_<?= $fault['id'] ?>_<?= $service['id'] ?>" value="<?= $service['id'] ?>">
                            <label for="additional_service_<?= $fault['id'] ?>_<?= $service['id'] ?>"><?= $service['name'] ?> - <?= $service['price'] ?> руб.</label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>

        <input type="submit" value="Оставить заявку">
    </form>

    <script>
        // Получаем все чекбоксы типов неисправностей
        var faultCheckboxes = document.querySelectorAll('input[type="checkbox"][name="fault_type[]"]');

        // Добавляем обработчик событий для каждого чекбокса
        faultCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // Получаем id текущего выбранного типа неисправности
                var faultTypeId = this.value;
                
                // Если чекбокс был выбран
                if (this.checked) {
                    // Показываем блок с дополнительными услугами для данного типа неисправности
                    var additionalServicesBlock = document.getElementById('additional_services_' + faultTypeId);
                    if (additionalServicesBlock) {
                        additionalServicesBlock.style.display = 'block';
                    }
                } else {
                    // Если чекбокс был снят, скрываем блок с дополнительными услугами для данного типа неисправности
                    var additionalServicesBlock = document.getElementById('additional_services_' + faultTypeId);
                    if (additionalServicesBlock) {
                        additionalServicesBlock.style.display = 'none';
                        
                        // Очищаем все радиокнопки в блоке с дополнительными услугами
                        additionalServicesBlock.querySelectorAll('input[type="radio"]').forEach(function(radio) {
                            radio.checked = false;
                        });
                    }
                }
            });
        });
    </script>
</body>
</html>