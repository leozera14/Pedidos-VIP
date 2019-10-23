<?php

include 'verifica_login.php';
include 'conexao.php';
include 'oracle.php';

if (!isset($_SESSION)) {
    session_start();
}

error_reporting(0);
header('Content-Type: text/html; charset=utf-8');


# Fornecedores
$query_fornecedores = "SELECT nome, cpf_or_cnpj FROM fornecedores ORDER BY nome ASC LIMIT 40";
$result1 = mysqli_fetch_all(mysqli_query($connect, $query_fornecedores), MYSQLI_ASSOC);
mysqli_close($connect);
# Produtos

$stid = oci_parse($ora_conexao, "SELECT b.codacesso, a.desccompleta, c.embalagem, b.qtdembalagem
from map_produto a, map_prodcodigo b, Map_Famembalagem c, mrl_produtoempresa d, mrl_departamento f, map_famdivcateg ff, map_categoria cc
where a.seqproduto = b.seqproduto
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
and	  ROWNUM <= 30
and   f.descricao = 'HORTIFRUTI'
ORDER BY a.desccompleta ASC");
oci_execute($stid);
$result2 = oci_fetch_all($stid, $res, null, null, OCI_FETCHSTATEMENT_BY_ROW);
oci_free_statement($stid);
oci_close($ora_conexao);
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Essas duas linhas são do Bootstrap, deixa a formatação mais 'bonita' -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Pedidos - Varejão Irmãos Patrocinio</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <script src="js/jquery.mask.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/script.js"></script>
</head>


<body onload="dataAtual()">

    <div class="area"></div>
    <nav class="main-menu">
        <ul>
            <li>
                <a href="consulta_pedidos.php">
                    <i class="glyphicon glyphicon-search glyphicon-2x"></i>
                    <span class="nav-text">Consultar pedidos</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="dropdown" id="second-nav">
        <button class="btn btn-default dropdown-toggle" type="button" id="menu-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="glyphicon glyphicon-user" id="icon-usuario"></i><input id="nome-usuario" name="nome-usuario" class="input-div" value="<?= $_SESSION['nome'] ?>" readonly="true">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="menu-dropdown">
            <li><a href="altera_dados.php">Alterar Dados <span class="glyphicon glyphicon-cog"></span></a></li>

            <?php
            if (isset($_SESSION['admin'])) :
                ?>
                <li><a href="painel.php">Painel <span class="glyphicon glyphicon-th-list"></span></a></li>

            <?php
            endif;
            ?>

            <li><a href="logout.php">Sair <span class="glyphicon glyphicon-off"></span></a></li>
        </ul>
    </div>

    <div class="container-fluid seletor">
        <div class="wrap">
            <form action="pedido.php" method="POST" id="form-send">
                <div id="duplicata" class="duplicata" name="duplicata[]">
                    <div class="container" id="cabecalho">
                        <br>
                        <header class="cabecalho">
                            <div class="wrap-header">
                                <div class="imagem-header">
                                    <img src="imagens/logo_ip.png" alt="Logo Varejão Irmãos Patrocinio">
                                    <h1>Pedidos - Varejão Irmãos Patrocinio</h1>
                                </div>
                                <div class="dados-header">
                                    <div id="pedido">
                                        <b>ID do Pedido: </b>
                                        <input id="id-pedido" class="input-div" readonly="true" name="id_pedido">
                                    </div>


                                    <div id="loja">
                                        <b>Loja:</b> <input type="text" id="loja" name="loja" class="input-div" value="<?= $_SESSION['loja']; ?>" readonly="true">
                                    </div>

                                    <div id="data">
                                        <b>Data: </b>
                                        <input id="data-atual" class="input-div" readonly="true" name="data_atual">
                                    </div>
                                </div>

                            </div>
                        </header>
                    </div>

                    <div class="container" id="fornecedores">

                        <div class="title-padrao">
                            <h1 class="text-center">Fornecedor</h1>
                        </div>

                        <div class="fornecedores-wrap col-lg-12">
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 select_height text-center" id="div_id_fornecedor">
                                <b>ID:</b>
                                <input id="fornecedor-id" class="font-pop input-div" name="fornecedor_id" readonly="true" required>
                            </div>

                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-8 select_height" id="div_fornecedores">
                                <!-- selectpicker é o elemento que coloca o input e o select juntos -->

                                <b id="text-fornecedor">Selecione um fornecedor:</b>
                                <select class="selectpicker form-control" data-show-subtext="false" data-live-search="true" name="select_fornecedor" id="select_fornecedor" onchange="initFornecedores()" required>
                                    <?php
                                    echo '<option disabled selected hidden value="Selecione um fornecedor..." data-subtext="CNPJ do Fornecedor...">
                                    Selecione um fornecedor...</option>';
                                    foreach ($result1 as $item_fornecedores) {
                                        echo '<option data-subtext="' . $item_fornecedores['cpf_or_cnpj'] . '" value="'
                                            . $item_fornecedores['nome'] . '">' . $item_fornecedores['nome'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <input type="text" name="fornecedor_new_input" id="fornecedor_new_input" style="display: none" placeholder="Digite o nome do Fornecedor...">
                                <input type="checkbox" id="change_fornecedor" name="change_fornecedor" value="Fornecedor não cadastrado">&nbsp;
                                <label for="change_fornecedor" id="checkbox-fornecedor-text">Fornecedor
                                    não cadastrado</label>
                            </div>
                            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-3 text-center select_height" id="div_cnpj_fornecedor">
                                <b>CNPJ</b>
                                <input name="cnpj" minlength="12" maxlength="14" class="font-pop" id="cnpj" value="00.000.000/0000-00" required>
                            </div>
                        </div>
                    </div>

                    <div class="container" id="produtos">
                        <div class="separator"></div>

                        <div class="title-padrao">
                            <h1 class="text-center">
                                Produtos
                            </h1>
                        </div>

                        <div id="allProducts">
                            <section id="all-section">

                                <div class="inform-produtos">

                                    <div class="primeira inform-relative">
                                        <b>Item:</b>
                                    </div>

                                    <div class="segunda inform-relative">
                                        <b>Cod.:</b>
                                    </div>

                                    <div class="terceira inform-relative">
                                        <b>Selecione um produto:</b>
                                    </div>

                                    <div class="quantidade-embalagem inform-relative">
                                        <b>Qtd. Embalagem:</b>
                                    </div>

                                    <div class="quarta inform-relative">
                                        <b>Embalagem:</b>
                                    </div>

                                    <div class="quinta inform-relative">
                                        <b>Preço:</b>
                                    </div>

                                    <div class="sexta inform-relative">
                                        <b>Quantidade:</b>
                                    </div>

                                    <div class="setima inform-relative">
                                        <b>Preço Produto:</b>
                                    </div>
                                </div>

                                <div class="clone-prod" name="clone-prod[]">

                                    <div class="wrap-prod" name="wrap-prod[]">

                                        <div class="produtos-wrap" name="produtos-wrap[]">
                                            <div class="text-center select_height produto-padrao" id="primeiro-produto">
                                                <input type="text" class="index font-pop input-div" id="index_produto" name="index_produto[]" value="1" readonly="true" required>
                                            </div>

                                            <div class="text-center select_height produto-padrao" id="segundo-produto">
                                                <input class="font-pop number_id_produto input-div" value="" readonly="true" name="id_produto[]" required>
                                            </div>

                                            <div class="text-center select_height produto-padrao terceiro-produto" id="terceiro-produto" name="terceiro-produto[]">
                                                <select class="selectpicker form-control" data-show-subtext="false" data-live-search="true" name="select_produtos[]" id="select_produtos" onchange="initProdutos(this)" required>
                                                    <?php
                                                    echo '<option disabled selected hidden
                                                    value="Selecione um produto..."
                                                    data-subtext="Selecione um produto...">Selecione um produto...
                                                    </option>';
                                                    foreach ($res as $item_produtos) {
                                                        echo '<option data-subtext="' . $item_produtos['CODACESSO'] . '" value="'
                                                            . $item_produtos['CODACESSO'] . "|" . $item_produtos['EMBALAGEM'] . "|"
                                                            . $item_produtos['QTDEMBALAGEM'] . "|" . $item_produtos['DESCCOMPLETA'] . '">' 
                                                            . $item_produtos['DESCCOMPLETA'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="text-center select_height produto-padrao" id="quantidade-embalagem">
                                                <input type="text" class="edit-input font-pop" name="qtdembalagem[]" value="" required>
                                            </div>

                                            <div class="text-center select_height produto-padrao" id="quarto-produto">
                                                <input type="text" maxlength="2" class="edit-input font-pop" name="embalagem[]" value="" required>
                                            </div>

                                            <div class="text-center select_height produto-padrao" id="quinto-produto">
                                                <input type="number" id="preco-input" name="preco[]" step="0.01" min="0" class="edit-input font-pop" required>
                                            </div>

                                            <div class="text-center select_height produto-padrao" id="sexto-produto">
                                                <input type="number" id="qtd-input" step="0.01" min="0" class="edit-input font-pop" value="" name="quantidade-produto[]" required>
                                            </div>

                                            <div class="text-center select_height produto-padrao" id="setimo-produto">
                                                <input class="font-pop preco-produto input-div" readonly="true" name="preco-produto[]" required>
                                            </div>
                                        </div>

                                        <div class="text-center select_height produto-padrao oitavo-produto" id="div-remove">
                                            <button type="button" class="remover glyphicon glyphicon-remove button-produto"></button>
                                        </div>

                                    </div>

                                </div>

                            </section>
                            <div id="wrap-addbutton">
                                <button type="button" id="add-button" class="glyphicon glyphicon-plus-sign button-produto"></button>
                                <b>Adicione um produto...</b>
                            </div>
                        </div>

                    </div>

                    <div class="container" id="produto-total">
                        <div class="col-lg-12">
                            <div class="assinatura col-lg-9">
                                <div id="wrap-assinatura" class="text-center">
                                    <div id="assinatura"></div>
                                    <b>Assinatura</b>
                                </div>
                            </div>

                            <div class="preco-final col-lg-12 text-right">
                                <b>Preço Total:</b>
                                <br>
                                <input id="total" readonly="true" name="total_pedido" class="text-right input-div" value="R$ 0.00">
                            </div>
                        </div>
                    </div>

                    <div class="container" id="envia-formulario">

                        <input onclick="segundaVia()" type="button" id="segunda-via" value="Concluir Pedido" data-toggle="modal" data-target="#myModal">

                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Confirmação de Pedido</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>Deseja concluir o pedido ?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success" name="enviar" id="enviar-button" onclick="imprimir()" value="Concluir">Concluir
                                        </button>
                                        <button type="button" class="btn btn-danger" id="cancelarVia" onclick="cancelaVia()" data-dismiss="modal" value="Cancelar">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
            <div id="pontilhado"></div>
        </div>

        <footer id="copy">
            <div>
                <p>Copyright &copy; 2019 - Varejão Irmãos Patrocinio</p>
                <p>Todos os direitos reservados.</p>
            </div>
        </footer>
    </div>

    <script>
        initPedido();
    </script>

    <script>
        function limitaTotalPreco(evt) {
            var input = evt.target;
            var value = input.value;

            if (value.length <= 6) {
                return;
            }

            input.value = input.value.substr(0, 6);
        }

        function limitaTotalQTD(evt) {
            var input = evt.target;
            var value = input.value;

            if (value.length <= 4) {
                return;
            }

            input.value = input.value.substr(0, 4);
        }

        document.getElementById("preco-input").addEventListener('input', limitaTotalPreco);
        document.getElementById("qtd-input").addEventListener('input', limitaTotalQTD);
    </script>

    <script>
        $("#form-send").validate({
            required: true;
        });
    </script>
</body>
<object id="factory" style="display:none" classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814" viewastext codebase="../Includes/ScriptX.cab#Version=5,0,4,185">
</object>

</html>