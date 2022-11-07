<?php
$parool='vlad';
$sool='vagavagatekst';
$krypt=crypt($parool, $sool);
echo $krypt;
