<?php
$serverinimi="localhost"; // d70420.mysql.zonevs.eu
$kasutaja="vlad21"; // d70420_merk21
$parool="12345"; // ''
$andmebaas="vlad21"; //d70420_merk21

$yhendus=new mysqli($serverinimi, $kasutaja, $parool, $andmebaas);

$yhendus->set_charset('UTF8');

/*CREATE TABLE laulud(
id int primary key AUTO_INCREMENT,
laulunimi varchar(50),
lisamisaeg datetime,
punktid int Default 0,
kommentaarid text
)
  */
?>