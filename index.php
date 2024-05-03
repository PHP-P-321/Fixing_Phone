<?php

session_start();

if(empty($_COOKIE['id_user'])) {
    header("Location: ./login.php");
}

require_once("./db/db.php");

$select_types_of_fault = mysqli_query($connect, "SELECT * FROM `type_of_fault`");
$select_types_of_fault = mysqli_fetch_all($select_types_of_fault);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <style>
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