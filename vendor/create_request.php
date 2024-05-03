<?php 

session_start();

if(empty($_COOKIE['id_user'])) {
    header("Location: ../login.php");
    exit; // Выход из скрипта после перенаправления
}

require_once("../db/db.php");

$id_performer = $_POST['id_performer'];
$id_client = $_POST['id_client'];
$name_phone = $_POST['name_phone'];
$model_phone = $_POST['model_phone'];
$serial_number = $_POST['serial_number'];
$fault_types = $_POST['fault_type']; // Массив выбранных типов неисправностей

// Переменные для дополнительных услуг для каждого типа неисправности
$additional_services = array();
foreach ($fault_types as $fault_type) {
    // Формируем имя переменной для дополнительной услуги по текущему типу неисправности
    $additional_service_key = 'additional_service_' . $fault_type;
    // Проверяем, существует ли соответствующая переменная в массиве POST
    if (isset($_POST[$additional_service_key])) {
        // Если да, то добавляем значение в массив дополнительных услуг
        $additional_services[$fault_type] = $_POST[$additional_service_key];
    } else {
        // Если нет, то присваиваем значение null
        $additional_services[$fault_type] = null;
    }
}

$fault_types_str = implode(',', $fault_types);
$additional_services_str = implode(',', $additional_services);

// Выполняем запрос на вставку данных
mysqli_query($connect, "INSERT INTO `requests`
                        (`id_performer`, `id_client`, `name_phone`, `model_phone`, `serial_number`, `fault_type`, `additional_service`)
                        VALUES
                        ('$id_performer', '$id_client', '$name_phone', '$model_phone', '$serial_number', '$fault_types_str', '$additional_services_str')
");

header("Location: ../index.php");
