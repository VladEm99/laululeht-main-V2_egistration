<?php
require_once ('conf.php');
global $yhendus;
    //FILTER_SANITIZE_STRING - Удаляет теги и кодирует двойные
    //и одинарные кавычки, при необходимости удаляет или кодирует специальные символы.

    $login = filter_var(trim($_POST['login']),
    FILTER_SANITIZE_STRING);
    $pass = filter_var(trim($_POST['pass']),
    FILTER_SANITIZE_STRING);
    //проверка длинны логина и пароля
    if(mb_strlen($login) < 4 || mb_strlen($login) > 90) {
        echo "Login is too short or too long :(";
        exit();
    } else if(mb_strlen($pass) < 4 || mb_strlen($pass) > 90) {
    echo "Pass is too short or too long :(";
    exit();
    }

    $sool = 'vagavagatekst';
    $krypt = crypt($pass, $sool);

    $yhendus->query("INSERT INTO `kasutajad` (`nimi`, `parool`)
    VALUES('$login', '$krypt')");

    $yhendus->close();
?>

