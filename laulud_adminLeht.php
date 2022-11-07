<?php
require_once ('conf.php');
global $yhendus;
session_start();
if(!isset($_SESSION['tuvastamine'])){
    header('Location: loginAB.php');
    exit();
}
// tabeli andmete lisamine

if(!empty($_REQUEST["uusnimi"])){

    $kask=$yhendus->prepare("INSERT INTO laulud(laulunimi, lisamisaeg) VALUES (?, NOW())");
    $kask->bind_param('s', $_REQUEST["uusnimi"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//laulude komenteerimine
if(isSet($_REQUEST['uus_komment'])){
        $kask = $yhendus->prepare("UPDATE laulud SET kommentaarid=Concat(kommentaarid, ?) WHERE id=?");
        $lisakomentaar = ($_REQUEST['komment'] . "\n"); //"\n" - murra teksti ridu
        $kask->bind_param("si", $lisakomentaar, $_REQUEST['uus_komment']);
        $kask->execute();
        header("location: $_SERVER[PHP_SELF]");}
// punktide lisamine
if(isset($_REQUEST['haal'])) {
$kask = $yhendus->prepare("UPDATE laulud SET punktid=punktid+1 Where id=?");
$kask->bind_param('s', $_REQUEST['haal']);
$kask->execute();}

//punktide kustutamine
if(isset($_REQUEST['minus'])) {
    $kask = $yhendus->prepare("UPDATE laulud SET punktid=punktid-1 Where id=?");
    $kask->bind_param('s', $_REQUEST['minus']);
    $kask->execute();
}
//peitmine
if(isset($_REQUEST['peitmine'])) {
    $kask = $yhendus->prepare("UPDATE laulud SET avalik=0 Where id=?");
    $kask->bind_param('s', $_REQUEST['peitmine']);
    $kask->execute();
}
//naitamine
if(isset($_REQUEST['naitamine'])) {
    $kask = $yhendus->prepare("UPDATE laulud SET avalik=1 Where id=?");
    $kask->bind_param('s', $_REQUEST['naitamine']);
    $kask->execute();
}

//delete
if(isset($_REQUEST['kustuta'])) {
    $kask = $yhendus->prepare("DELETE FROM laulud Where id=?");
    $kask->bind_param('s', $_REQUEST['kustuta']);
    $kask->execute();
}

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Laulude adminleht</title>
    <link rel="stylesheet" type="text/css" href="style-admin.css">
</head>
<body>
<h1>Laulude administreerimise leht</h1>
<nav>
    <a href="laulu-lisamine.php">Home page</a>
    <a href="laulud_adminLeht.php">Administreerimise leht</a>
    <a href="laulud.php">Kasutaja leht</a>
    <a href="link">Git HUB</a>
</nav>
<br>
<br>

<table class="zigzag">
    <tr>
        <th>Laulude Kustutamine</th>
        <th>Laulunimi</th>
        <th>Punktid</th>
        <th>Lisamisaeg</th>
        <th>Staatus</th>
        <th>Haldus</th>
        <th>lisa punktid..</th>
        <th>kustuta punktid..</th>
        <th>Kommentaarid</th>
        <th>Kommentaaride redigeerimine</th>

    </tr>
    <?php
    // tabeli sisu näitamine
    $kask=$yhendus->prepare('SELECT id, laulunimi, punktid, lisamisaeg, avalik 
FROM laulud');
    $kask->bind_result($id, $laulunimi, $punktid, $aeg, $avalik);
    $kask->execute();
    while($kask->fetch()){
        $seisund="Peidetud";
        $param="naitamine";
        $tekst="Näita";
        if($avalik==1){
            $seisund="Avatud";
            $param="peitmine";
            $tekst="Peida";
        }
        echo "<tr>";
        echo "<td><a href='?kustuta=$id'>Kustuta</a></td>";
        echo "<td>".htmlspecialchars($laulunimi)."</td>";
        echo "<td>$punktid</td>";
        echo "<td>$aeg</td>";
        echo "<td>".($seisund)."</td>";
        echo "<td><a href='?$param=$id'>$tekst</a></td>";
        echo "<td><a href='?haal=$id'>lisa +1 punkt</a></td>";
        echo "<td><a href='?minus=$id'>kustuta -1 punkt</a></td>";
        echo "<td></td>";
        echo "<td>
            <form action='?'>
            <input type='hidden' name='uus_komment' value='$id'>
            <input type='text' name='komment'>
            <input type='submit' value='Lisa'>
            <form>
            </td>";
        echo "</tr>";



        echo "</tr>";
    }


    ?>

</table>
</body>
<?php
$yhendus->close();
//Ülesanne:
// Admin lehel - laulu kustutamine
// css table style
// Admin lehel -punktid nulliks
// Üldine Navigeerimismenüü / adminleht/ kasutajaleht
//admin näeb kommentaarid ja saab neid kustutada

?>
</html>

