<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

include "conexao.php";

date_default_timezone_set('America/Sao_Paulo');

$idPedido = $_GET['idPedido'];

$query = "SELECT * FROM pedido_item WHERE id_pedido = {$idPedido}";
$arrayProd = mysqli_fetch_all(mysqli_query($connect, $query), MYSQLI_ASSOC);

$linha = [];
$filename = 'Pedido_'. $idPedido . '.txt';
$arquivo = fopen($filename,'w');
//verificamos se foi criado
if ($arquivo == false) die('Não foi possível criar o arquivo.');


foreach ($arrayProd as $key => $produto) {
    $init = str_pad('', 7, "0", STR_PAD_LEFT);
    $cod = str_pad($produto['id_produto'], 13, "0", STR_PAD_LEFT);
    $qtd = str_pad($produto['quantidade'], 6, "0", STR_PAD_LEFT);

    $codigo = $init . $cod . $qtd . "\n";

    fwrite($arquivo, $codigo);
}

//Fechamos o arquivo após escrever nele
fclose($arquivo);

header('Content-disposition: attachment; filename=' . $filename);
header('Content-type: application/txt');
header('Content-Transfer-Encoding: binary');
header('Content-Description: File Transfer');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
ob_clean();
flush();
readfile($filename);
?>
