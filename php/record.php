<?php
header('Access-Control-Allow-Origin: *');
$database = new mysqli("localhost", "root", "123567", "surfing");
$file = $_POST["file"];

$database->query("UPDATE `ghostplayer` SET text = '$file'");