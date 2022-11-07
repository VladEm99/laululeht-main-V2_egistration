<link rel="stylesheet" href="style-kasutaja.css">
<?php
require_once ('conf.php');
global $yhendus;
// tabeli andmete lisamine
if(!empty($_REQUEST["uusnimi"])){

    $kask=$yhendus->prepare("INSERT INTO laulud(laulunimi, lisamisaeg) VALUES (?, NOW())");
    $kask->bind_param('s', $_REQUEST["uusnimi"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    header("Location: laulud.php");
    die();
}

//laulude komenteerimine
if(isSet($_REQUEST['uus_komment'])){
    $kask=$yhendus->prepare("UPDATE laulud SET kommentaarid=Concat(kommentaarid, ?) WHERE id=?");
        $lisakomentaar = ($_REQUEST['komment']."\n"); //"\n" - murra teksti ridu
        $kask->bind_param("si", $lisakomentaar, $_REQUEST['uus_komment']);
        $kask->execute();
        header("location: $_SERVER[PHP_SELF]");
}

// punktide lisamine
if(isset($_REQUEST['haal'])) {
    $kask = $yhendus->prepare("UPDATE laulud SET punktid=punktid+1 Where id=?");
    $kask->bind_param('s', $_REQUEST['haal']);
    $kask->execute();
// aadressiriba sisu eemaldamine
    header("Location: $_SERVER[PHP_SELF]");
}
//punktide kustutamine
if(isset($_REQUEST['minus'])) {
    $kask = $yhendus->prepare("UPDATE laulud SET punktid=punktid-1 Where id=?");
    $kask->bind_param('s', $_REQUEST['minus']);
    $kask->execute();
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Laulude leht</title>
</head>
<body>
<h1>Home Page</h1>
<nav>
    <a href="laulu-lisamine.php">Home page</a>
    <a href="laulud_adminLeht.php">Administreerimise leht</a>
    <a href="laulud.php">Kasutaja leht</a>
    <a href="https://github.com/VladEm99/laululeht-main-V1">Git HUB</a>
</nav>
<h2>Laulu lisamine</h2>
<form action="?" method="post">
    <label for="nimi">Laulunimi</label>
    <input type="text" name="uusnimi" id="nimi" placeholder="laulunimi">
    <input type="submit" value="Ok">
</form>


<table>
    <tr>
        <th>Laulunimi</th>
        <th>Punktid</th>
        <th>Lisamisaeg</th>
        <th>kommentaarid</th>
    </tr>
    <?php
    // tabeli sisu näitamine
    $kask=$yhendus->prepare('SELECT id, laulunimi, punktid, lisamisaeg, kommentaarid
FROM laulud Where avalik=1');
    $kask->bind_result($id, $laulunimi, $punktid, $aeg, $kommentaarid);
    $kask->execute();
while($kask->fetch()){
    echo "<tr>";
    echo "<td>".htmlspecialchars($laulunimi)."</td>";
    echo "<td>$punktid</td>";
    echo "<td>$aeg</td>";
    //echo "<td><a href='?haal=$id'>lisa +1 punkt</a></td>";
    //echo "<td><a href='?minus=$id'>kustuta -1 punkt</a></td>";
    echo "<td>".nl2br($kommentaarid)."</td>"; //nl2br - break function before newlines in str
    echo "<td>
            <form action='?'>
            <input type='hidden' name='uus_komment' value='$id'>
            <input type='text' name='komment'>
            <input type='submit' value='OK'>
            <form>
            </td>";
    echo "</tr>";
}


    ?>

</table>
</body>
<?php
$yhendus->close();
?>
</html>