<?php

session_start();

if(empty($_COOKIE['id_user'])) {
    header("Location: ./login.php");
}

require_once("./db/db.php");

$select_types_of_fault = mysqli_query($connect, "SELECT * FROM `type_of_fault`");
$select_types_of_fault = mysqli_fetch_all($select_types_of_fault);

$id_user = $_COOKIE['id_user'];
$select_requests = mysqli_query($connect, "SELECT `id`, `name_phone`, `model_phone`, `serial_number`, `fault_type`, `additional_service` FROM `requests` WHERE `id_client`='$id_user'");
$select_requests = mysqli_fetch_all($select_requests);

// SQL-запрос для выбора типов неисправностей по их id из таблицы type_of_fault
$fault_types_ids = array_column($select_requests, 4); // Получаем массив id типов неисправностей
$fault_types_ids_str = implode(',', array_unique(explode(',', implode(',', $fault_types_ids)))); // Преобразуем массив в строку уникальных id типов неисправностей
$sql_fault_types = "SELECT * FROM `type_of_fault` WHERE `id` IN ($fault_types_ids_str)";
$result_fault_types = mysqli_query($connect, $sql_fault_types);
$fault_types = mysqli_fetch_all($result_fault_types, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
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
    <a href="./logout.php">Выйти</a>    

    <?php if($_COOKIE['role'] == 1) { ?>
        
    <?php } elseif($_COOKIE['role'] == 2) { ?>
        <div class="filter">
            <select name="types_of_fault" id="types_of_fault">
                <?php foreach($select_types_of_fault as $type_of_fault) { ?>
                    <option value="<?= $type_of_fault[0] ?>"><?= $type_of_fault[1] ?></option>
                <?php } ?>
            </select>
            <button id="filter_button">Фильтровать</button>
        </div>
        <br>
        <div id="performers_list" style="display: flex; flex-direction: column; gap: 10px;"></div>

        <table>
            <thead>
                <tr>
                    <th>ID заявки</th>
                    <th>Название телефона</th>
                    <th>Модель телефона</th>
                    <th>Серийный номер</th>
                    <th>Типы неисправностей</th>
                    <th>Доп услуги</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($select_requests as $request) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($request[0]) . '</td>'; // ID заявки
                    echo '<td>' . htmlspecialchars($request[1]) . '</td>'; // Название телефона
                    echo '<td>' . htmlspecialchars($request[2]) . '</td>'; // Модель телефона
                    echo '<td>' . htmlspecialchars($request[3]) . '</td>'; // Серийный номер

                    // Выводим типы неисправностей
                    echo '<td>';
                    $fault_type_ids = explode(',', $request[4]); // Получаем id типов неисправностей
                    $fault_type_names = []; // Массив для хранения имен типов неисправностей
                    foreach ($fault_types as $type) {
                        if (in_array($type['id'], $fault_type_ids)) {
                            $fault_type_names[] = htmlspecialchars($type['name_type']); // Добавляем имя типа неисправности в массив
                        }
                    }
                    echo implode(', ', $fault_type_names); // Выводим имена типов неисправностей, разделенные запятыми
                    echo '</td>';

                    echo '<td>';
                    $total_additional_services_price = 0;

                    // Проходим по всем типам неисправностей в текущей заявке
                    $fault_type_ids = explode(',', $request[4]); // Получаем id типов неисправностей в текущей заявке
                    $fault_type_service_ids = explode(',', $request[5]); // Получаем id дополнительных услуг в текущей заявке

                    // Массив для хранения данных о дополнительных услугах для каждого типа неисправности
                    $fault_type_additional_services = [];

                    foreach ($fault_types as $type) {
                        if (in_array($type['id'], $fault_type_ids)) {
                            // Получаем данные о дополнительных услугах для текущего типа неисправности
                            $additional_services_data = json_decode($type['additional_services'], true)['additional_services'];

                            // Проходим по каждой дополнительной услуге
                            foreach ($additional_services_data as $service) {
                                // Проверяем, есть ли данная дополнительная услуга в текущей заявке
                                if (in_array($service['id'], $fault_type_service_ids)) {
                                    // Добавляем данные о дополнительной услуге в массив
                                    $fault_type_additional_services[$type['name_type']][] = $service;
                                    // Увеличиваем общую сумму дополнительных услуг
                                    $total_additional_services_price += $service['price'];
                                }
                            }
                        }
                    }

                    // Выводим данные о дополнительных услугах
                    foreach ($fault_type_additional_services as $fault_type_name => $additional_services) {
                        echo htmlspecialchars($fault_type_name) . ': ';
                        foreach ($additional_services as $service) {
                            echo htmlspecialchars($service['name']) . ' (' . $service['price'] . ' руб.), ';
                        }
                    }
                    echo '<br>Сумма ремонта: ' . $total_additional_services_price . ' руб.';
                    echo '</td>';


                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            $(document).ready(function() {
                $('#filter_button').click(function() {
                    var selectedIdType = $('#types_of_fault').val(); // Получаем выбранный id_type

                    $.ajax({
                        url: './vendor/select_performers.php',
                        type: 'POST',
                        data: {
                            id_type: selectedIdType
                        },
                        success: function(response) {
                            var performers = JSON.parse(response);
                            var html = '';

                            // Проверяем, есть ли результаты поиска
                            if (performers.length > 0) {
                                // Добавляем заголовок для результатов поиска
                                html += '<h2>Результаты поиска:</h2>';

                                // Перебираем массив исполнителей и добавляем их в HTML
                                for (var i = 0; i < performers.length; i++) {
                                    html += '<a href="./performer.php?id=' + performers[i].id + '">' + performers[i].name_performer + '</a><br>';
                                }
                            } else {
                                // Если нет результатов поиска, выводим сообщение об этом
                                html += '<p>Ничего не найдено.</p>';
                            }

                            // Очищаем содержимое performers_list перед добавлением новых данных
                            $('#performers_list').empty();

                            // Выводим результаты поиска на страницу
                            $('#performers_list').html(html);
                        },
                        error: function() {
                            alert('Ошибка при выполнении AJAX запроса');
                        }
                    });
                });
            });
        </script>
    <?php } ?>

</body>
</html>