<?php
//lisame oma kasutajanimi, parooli, ja ab_nimi
$serverinimi="localhost"; // d70420.mysql.zonevs.eu
$kasutaja="vlad21"; // d70420_merk21
$parool="12345"; // ''
$andmebaas="vlad21"; //d70420_merk21

$yhendus=new mysqli($serverinimi, $kasutaja, $parool, $andmebaas);

$yhendus->set_charset('UTF8');
session_start();
$error = $_SESSION['error'] ?? "";

function puhastaAndmed($data){
    //trim()- eemaldab tühikud
    $data=trim($data);
    //htmlspecialchars - ignoreerib <käsk>
    $data=htmlspecialchars($data);
    //stripslashes - eemaldab \
    $data=stripslashes($data);
    return $data;
}
if(isset($_REQUEST["knimi"])&& isset($_REQUEST["psw"])) {

    $login = puhastaAndmed($_REQUEST["knimi"]);
    $pass = puhastaAndmed($_REQUEST["psw"]);
    $sool = 'vagavagatekst';
    $krypt = crypt($pass, $sool);

//kasutajanimi kontroll
    $kask = $yhendus->prepare("SELECT id, unimi, psw FROM uuedkasutajad
WHERE unimi=?");
    $kask->bind_param("s", $login);
    $kask->bind_result($id, $kasutajanimi, $parool);
    $kask->execute();
    if ($kask->fetch()) {
        $_SESSION['error'] = "Kasutaja on juba olemas";
        header("Location: $_SERVER[PHP_SELF]");
        $yhendus->close();
        exit();

    } else {
        $_SESSION['error'] = " ";
    }


// uue kasutaja lisamine andmetabeli sisse
    $kask = $yhendus->prepare("
INSERT INTO uuedkasutajad(unimi, psw, isadmin) 
VALUES (?,?,?)");
    $kask->bind_param("ssi", $login, $krypt, $_REQUEST["admin"]);
    $kask->execute();
    $_SESSION['unimi'] = $login;
    $_SESSION['admin'] = true;
    header("location: laulud.php");
    $yhendus->close();
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registreerimisvorm</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="css/login.css" type="text/css">
</head>
<body>

<div id="id01">
    <form  class="modal-content animate" action="registr.php" method="post">
        <div class="imgcontainer">
            <img src="img/avatar.png" alt="Avatar" class="avatar">
        </div>
        <div class="container">
            <h1>Uue kasutaja registreerimine</h1>
            <label for="knimi">Kasutajanimi</label>
            <input type="text" placeholder="Sisesta kasutajanimi"
                   name="knimi" id="knimi" required>
            <br>
            <label for="psw">Parool</label>
            <input type="password" placeholder="Sisesta parool"
                   name="psw" id="psw" required>
            <br>
            <label for="admin">Kas teha admin?</label>
            <input type="checkbox" name="admin" id="admin" value="1">
            <br>
            <input type="submit" value="Loo kasutaja">
            <button type="button"
                    onclick="window.location.href='laulud.php'"
                    class="cancelbtn">Loobu</button>

            <strong> <?=$error ?></strong>
        </div>
    </form>
</div>
</body>
</html>
