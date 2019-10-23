<?php
session_start();
include('conexao.php');

if(empty($_POST['nome'])|| empty($_POST['usuario'])|| empty($_POST['senha'])){
    $_SESSION['dados_invalidos'] = true;
    header('Location: cadastro.php');
    exit();
}

$nome = mysqli_real_escape_string($connect, trim($_POST['nome']));
$usuario = mysqli_real_escape_string($connect, trim($_POST['usuario']));
$senha = mysqli_real_escape_string($connect, trim(md5($_POST['senha'])));
$loja = mysqli_real_escape_string($connect, $_POST['loja']);

$sql = "SELECT COUNT(*) as total FROM usuario WHERE usuario = '$usuario'";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

if($row['total'] == 1) {
    $_SESSION['usuario_existe'] = true;
    header('Location: cadastro.php');
    exit;
}

$sql = "INSERT INTO usuario(nome, usuario, senha, loja, data_cadastro) VALUES ('$nome', '$usuario', '$senha', '$loja', NOW())";


if($connect->query($sql) === TRUE && $_SESSION['nivel'] == 2) {
    $_SESSION['status_cadastro_admin'] = true;
    sleep(2);
    header('Location: painel.php');
    exit;
} else {
    $_SESSION['status_cadastro'] = true;
    sleep(2);
    header('Location: index.php');
    exit;
}



$connect->close();
exit;
?>