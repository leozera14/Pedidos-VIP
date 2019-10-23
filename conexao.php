<?php
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$dbname = 'valevip';

$connect = mysqli_connect($servidor, $usuario, $senha, $dbname);

if ($connect -> connect_error){
    die("Conexão falhou: " . $connect->connect_error);
}