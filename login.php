<?php
session_start();
include('conexao.php');

if(empty($_POST['usuario'])|| empty($_POST['senha'])){
    $_SESSION['login_vazio'] = true;
    header('Location: index.php');
    exit();
}

$usuario = mysqli_real_escape_string($connect, $_POST['usuario']);
$senha = mysqli_real_escape_string($connect, $_POST['senha']);

$query = "SELECT * FROM usuario where usuario = '{$usuario}' and senha = md5('{$senha}')";

$result = mysqli_query($connect, $query);

$row = mysqli_num_rows($result);

if($row == 1) {
    $usuario_bd = mysqli_fetch_assoc($result);
    $_SESSION['id_usuario'] = $usuario_bd['id_usuario'];
    $_SESSION['nome'] = $usuario_bd['nome'];
    $_SESSION['loja'] = $usuario_bd['loja'];
    $_SESSION['nivel'] = $usuario_bd['nivel'];
    if($usuario_bd['nivel'] == 2) {
        $_SESSION['admin'] = true;
        header('Location: painel.php');
        exit;
    } else{
        header('Location: principal.php');
        exit;
    }
} else {
    $_SESSION['nao_autenticado'] = true;
    header('Location: index.php');
    exit();
}

mysqli_close($connect);

