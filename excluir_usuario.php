<?php
require_once 'verifica_login.php';
require_once 'conexao.php';

$id = intval($_GET['id']);


$query = "DELETE FROM usuario WHERE id_usuario = $id";


if($connect->query($query) === TRUE){
    header('Location: painel.php');
    exit();
}
?>