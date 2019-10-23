<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

include "conexao.php";
include "oracle.php";

// Coloquei esse MODE pq se vc busca fornecedores, não precisa trazer produtos, certo?
// Assim ele fica mais leve e busca só o que vc vai usar mesmo
switch ($_GET['mode']) {
	case 'fornecedores':
		$letras = $_GET['letras_fornecedores'];
		$query = "SELECT * FROM fornecedores WHERE nome LIKE '{$letras}%' ORDER BY nome ASC LIMIT 30";
		$result = mysqli_fetch_all(mysqli_query($connect, $query), MYSQLI_ASSOC);
		break;

	case 'ultimoPedido':
		$query = "SELECT id_pedido as ultimoPedido FROM pedido ORDER BY id_pedido DESC LIMIT 1";
		$result = mysqli_fetch_all(mysqli_query($connect, $query), MYSQLI_ASSOC);
		break;

	default:
		$letras = $_GET['letras_produtos'];
		$stid = oci_parse($ora_conexao,"SELECT b.codacesso, a.desccompleta, c.embalagem, b.qtdembalagem
		from map_produto a, map_prodcodigo b, Map_Famembalagem c, mrl_produtoempresa d, mrl_departamento f, map_famdivcateg ff, map_categoria cc
		where a.desccompleta LIKE UPPER('{$letras}%') 
		and   a.seqproduto = b.seqproduto
		and   a.seqproduto = d.seqproduto
		and   a.seqfamilia = ff.seqfamilia
		and   ff.seqcategoria = cc.seqcategoria
		and   ff.nrodivisao   = cc.nrodivisao
		AND   a.seqfamilia = c.seqfamilia
		and   d.nroempresa = f.nroempresa(+)
		and   cc.tipcategoria = 'M'
		and   ff.status = 'A'
		and   cc.nivelhierarquia = 2
		and   d.nrodepartamento = f.nrodepartamento(+)
		and   a.seqfamilia = ff.seqfamilia
		and   b.indutilvenda = 'S'
		and   ff.nrodivisao = 1
		and   b.tipcodigo in ('B','E')
		and   d.nrodepartamento = 4
		and   d.nroempresa = 1
		and   c.qtdembalagem = b.qtdembalagem
		and	  ROWNUM <= 60
		and   f.descricao = 'HORTIFRUTI'
		ORDER BY a.desccompleta ASC");
		oci_execute($stid);
		$res = oci_fetch_all($stid, $result, null, null, OCI_FETCHSTATEMENT_BY_ROW);
		oci_free_statement($stid);
		oci_close($ora_conexao);
		//$query = "SELECT * FROM produto WHERE desc_produto LIKE '{$letras}%' ORDER BY desc_produto ASC LIMIT 20";
		break;
}
mysqli_close($connect);

if($result == NULL)
    die('Nenhum resultado encontrado');

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);
