<?php
// Устанавливаем cookie с именем "id_user" на пустое значение и устанавливаем срок жизни в прошлое (-1), чтобы удалить cookie
setcookie("id_user", null, -1, "/");

// Перенаправляем на главную страницу
header("Location: ./index.php");
