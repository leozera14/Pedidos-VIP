<?php
require_once 'verifica_login.php';
require_once 'conexao.php';
error_reporting(E_ERROR);
header('Content-Type: text/html; charset=utf-8');


if(empty($_POST['usuario']) || empty($_POST['nome']) || empty($_POST['nova_senha']) || empty($_POST['confirma_senha']) || empty($_POST['loja'])){
    $_SESSION['dados_nulos'] = true;
    header('Location: altera_dados.php');  
    exit;
}

if (md5($_POST['nova_senha']) !== md5($_POST['confirma_senha'])) {
    $_SESSION['senha_invalida'] = true;
    header('Location: altera_dados.php');  
    exit;
}

$usuario = mysqli_real_escape_string($connect, trim($_POST['usuario']));
$nome = mysqli_real_escape_string($connect, trim($_POST['nome']));
$nova_senha = mysqli_real_escape_string($connect, trim(md5($_POST['nova_senha'])));
$confirma_senha = mysqli_real_escape_string($connect, trim(md5($_POST['confirma_senha'])));
$loja = $_POST['loja'];



$query = "UPDATE usuario SET nome = '$nome', usuario = '$usuario', senha = '$nova_senha', loja = '$loja' WHERE id_usuario = '$_SESSION[id_usuario]'";

if($connect->query($query) === TRUE) {
    session_destroy();
    sleep(3);
    session_start();
    $_SESSION['usuario_sucesso'] = true;
    header('Location: index.php'); 
}

$connect->close();
exit;
?>