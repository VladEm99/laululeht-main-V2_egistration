<?php
//login vorm koodis kirjutatud kasutajanimega ja prooliga
session_start();
if(isset($_SESSION['tuvastamine'])){
    header('Location: laulud.php');
    exit();

}
// login ja pass kontroll
if(!empty($_POST['login']) && !empty($_POST['pass'])){
    $login=$_POST['login'];
    $pass=$_POST['pass'];
    if($login=='admin' && $pass=='vlad'){
        $_SESSION['tuvastamine']='niilihtne';
        header('Location: laulud.php');
    }
}
/*
 * CREATE TABLE kasutajad(
    id int not null primary KEY AUTO_INCREMENT,
    nimi varchar(10),
    parool varchar(200),
    onAdmin tinyint,
    koduleht varchar(100))*/

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
<h1>Login vorm</h1>
<form action="" method="post">
    Login:
    <input type="text" name="login" placeholder="kasutaja nimi">
    <br>
    Parool:
    <input type="password" name="pass">
    <br>
    <input type="submit" value="Logi sisse">


</form>

</body>
</html>

