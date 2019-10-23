<?php
include('verifica_login.php');
include('conexao.php');



if ($_SESSION['nivel'] != 2) {
    header('Location: index.php');
    exit();
} 

$query = "SELECT * FROM usuario";
$usuarios = mysqli_fetch_all(mysqli_query($connect, $query), MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/painel.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
    <style>
        .container {
            border: 1px solid #1172ab;
            border-top: none;
        }
        #cadastra {
            margin-bottom: 1.5em;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div id="nav">
                <div>
                    <h1>Painel Administrativo</h1>
                </div>
                <ul class="nav nav-pills">
                    <li role="presentation"><a href="principal.php">Pedidos</a></li>
                    <li role="presentation"><a href="consulta_pedidos.php">Consultar Pedidos</a></li>
                    <li role="presentation"><a href="logout.php">Deslogar</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container-fluid">
        <div class="container">

            <?php 
             if(isset($_SESSION['altera_usuario_admin'])):
            ?>

            <div class="alert alert-success">
                <p>Dados atualizados com sucesso !</p>
            </div>

            <?php
                endif;
                unset($_SESSION['altera_usuario_admin']);
            ?>


            <?php
                if (isset($_SESSION['status_cadastro_admin'])):
            ?>
            <div class="alert alert-success">
                <p>Cadastro efetuado com sucesso!</p>
            </div>
            <?php
                endif;
                unset($_SESSION['status_cadastro_admin']);
            ?>

            <div style="padding: 20px 0 40px 0;">
                <h2>Usuários Cadastrados</h2>
            </div>

            <form class="table-responsive tabela">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Usuário</th>
                            <th>Loja</th>
                            <th>Nivel</th>
                            <th>Data de Cadastro</th>
                            <th>Editar</th>
                            <th>Excluir</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($usuarios as $usuario => $value) { ?>
                        <tr>
                            <td><?= $value['id_usuario'] ?></td>
                            <td><?= $value['nome'] ?></td>
                            <td><?= $value['usuario']?></td>
                            <td><?= $value['loja'] ?></td>
                            <td><?= $value['nivel'] ?></td>
                            <td><?= $value['data_cadastro'] ?></td>
                            <td><a href="altera_dados_admin.php?id=<?= intval($value['id_usuario']) ?>">Editar</a></td>
                            <td><a href="excluir_usuario.php?id=<?= intval($value['id_usuario']) ?>">Excluir</a></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </form>

            <a href="cadastro.php" id="cadastra" type="button" class="btn btn-success">Adicionar usuário</a>
        </div>
    </div>
</body>

</html>