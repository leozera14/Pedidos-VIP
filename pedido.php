<?php
include 'verifica_login.php';
include 'conexao.php';

error_reporting(E_ERROR);
header('Content-Type: text/html; charset=utf-8');

# Inclusão do Pedido


$id_fornecedor = $_POST['fornecedor_id'];
$nome_fornecedor = trim($_POST['select_fornecedor']);
$nome_input_fornecedor = trim($_POST['fornecedor_new_input']);
$cnpj = str_replace(".", "", str_replace("/", "", str_replace("-", "", $_POST['cnpj'])));

preg_match('/[\d|,|\.]+/', $_POST['total_pedido'], $valor_total);
$valor_total = str_replace(",", ".", str_replace(".", "", $valor_total[0]));

$gerente_fiscal = trim($_SESSION['nome']);
$loja = trim($_POST['loja']);

if (isset($_POST['change_fornecedor'])) {
    $sql = "INSERT INTO pedido VALUES (NULL, '$gerente_fiscal', '$id_fornecedor', '$nome_input_fornecedor','$cnpj', NOW(), '$valor_total', '$loja')";
    if (!$connect->query($sql) === true) {
        die("Erro na inserção de pedido: " . $sql . "<br>" . $connect->error);
    }
} else {
    $sql = "INSERT INTO pedido VALUES (NULL, '$gerente_fiscal', '$id_fornecedor', '$nome_fornecedor', '$cnpj', NOW(), '$valor_total', '$loja')";
    if (!$connect->query($sql) === true) {
        die("Erro na inserção de pedido: " . $sql . "<br>" . $connect->error);
    }
}

function multiexplode($delimiters, $string) {
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}
# Inserção de Itens de Pedido
$id_pedido = $connect->insert_id;
$qtd_itens = sizeof($_POST['index_produto']);
for ($i = 0; $i < $qtd_itens; $i++) {
    # Variáveis
    $item = $_POST['index_produto'][$i];
    $id_produto = $_POST['id_produto'][$i];

    $explode = multiexplode(array("|"), $_POST['select_produtos'][$i]);

    $desc_produto = $explode[3];

    $quantidade = str_replace(",", ".", $_POST['quantidade-produto'][$i]);

    $valor_un = str_replace(",", ".", $_POST['preco'][$i]);

    preg_match('/[\d|,|\.]+/', $_POST['preco-produto'][$i], $valor_produto);
    $valor_produto = str_replace(",", ".", str_replace(".", "", $valor_produto[0]));

    # Insert de Dados

    $query = "INSERT INTO pedido_item VALUES ('$id_pedido', '$item', '$id_produto', '$desc_produto', '$valor_un', '$quantidade', '$valor_produto')";
    if (!$connect->query($query) === true) {
        die("Erro na inserção de itens: " . $query . "<br>" . $connect->error);
    }
}

header('Location: principal.php');
