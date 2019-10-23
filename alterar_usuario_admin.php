<?php
require_once 'verifica_login.php';
require_once 'conexao.php';

error_reporting(E_ERROR);
header('Content-Type: text/html; charset=utf-8');

$id =intval($_SESSION['id_usuario']);


if(empty($_POST['usuario']) || empty($_POST['nome']) || empty($_POST['nova_senha']) || empty($_POST['confirma_senha'] || empty($_POST['loja']))){
    $_SESSION['dados_nulos'] = true;
    header('Location: altera_dados_admin.php');  
    exit;
}

if (md5($_POST['nova_senha']) !== md5($_POST['confirma_senha'])) {
    $_SESSION['senha_invalida'] = true;
    header('Location: altera_dados_admin.php');  
    exit;
}

$usuario = mysqli_real_escape_string($connect, trim($_POST['usuario']));
$nome = mysqli_real_escape_string($connect, trim($_POST['nome']));
$nova_senha = mysqli_real_escape_string($connect, trim(md5($_POST['nova_senha'])));
$confirma_senha = mysqli_real_escape_string($connect, trim(md5($_POST['confirma_senha'])));
$loja = $_POST['loja'];



$query = "UPDATE usuario SET nome = '$nome', usuario = '$usuario', senha = '$nova_senha', loja = '$loja' WHERE id_usuario = '$id'";

if(!$connect->query($query) === TRUE) {
   echo "Houve um erro ao atualizar o cadastro" . mysqli_error($connect);
   die();
} else {
    $_SESSION['altera_usuario_admin'] = true;
    sleep(3);
}

$connect->close();

header('Location: painel.php');  

exit;
?>