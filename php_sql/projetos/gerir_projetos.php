<?php
session_name('valida');
session_start();
use sys4soft\Database;
require_once('header.php');
require_once('../libraries/Database.php');
require_once('../libraries/config.php');
if (!isset($_SESSION['user_id']) || $_SESSION['funcao'] != 'admin') {
    header("Location: ../login._index.php");
    exit();
}
$database = new Database(MYSQL_CONFIG);
// Obter todos os projetos
$projetos = $database->execute_query("SELECT * FROM projetos")->results;
?>
<div class="container"  style="padding-bottom:50px;">
<h2>Gerir projetos</h2>
<div class="row align-items-center mb-3">
    <div class="col text-end">
        <a href="adicionar_projeto.php" class="btn btn-outline-dark">Adicionar projeto</a>
        <a href="ativos.php" class="btn btn-outline-dark">Projetos Ativos</a>
    </div>

<!-- Exibe a tabela de contatos -->
<div class="row">
    <div class="col">
            <!-- Com resultados -->
            <table class="table table-sm table-striped table-bordered">
                <thead class="bg-dark text-white">
                    <tr>
                        <th width="10%">Titulo:</th>
                        <th width="30%">Descricao:</th>
                        <th width="30%">Tecnologia:</th>
                    </tr>
                    </thead>
                <tbody>
                    <?php foreach ($projetos as $projeto): ?>
                    <tr>
                        <td><?= htmlspecialchars($projeto->titulo) ?></td>
                        <td><?= htmlspecialchars($projeto->descricao) ?></td>
                        <td><?= htmlspecialchars($projeto->tecnologia) ?></td>
                        <td class="text-center">
                            <a href="editar_projetos.php?id=<?= htmlspecialchars($projeto->id) ?>" class="btn btn-warning">Editar</a>
                        </td>
                        <td class="text-center">
                            <a href="excluir_projeto.php?id=<?= htmlspecialchars($projeto->id) ?>"class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
        </table> 
    </div>
    </div>
</div>
<?php require_once('footer.php');?>