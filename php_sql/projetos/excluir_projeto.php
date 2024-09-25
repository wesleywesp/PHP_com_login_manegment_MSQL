<?php
require_once('header.php');
use sys4soft\Database;
require_once('../libraries/Database.php');
require_once('../libraries/config.php');
$database = new Database(MYSQL_CONFIG);
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: gerir_projetos.php');
    exit();
}

$id = $_GET['id'];
$database = new Database(MYSQL_CONFIG);
$parames = [':id' => $id];

// Verificar se o contato existe
$results = $database->execute_query("SELECT * FROM projetos WHERE id = :id", $parames);
$projeto = $results->results[0] ?? null;
if (!$projeto) {
    // Se o contato não for encontrado, redireciona ou exibe uma mensagem de erro
    header('Location: gerir_projetos.php');
    exit();
}if(isset($_GET['delete']) && $_GET['delete'] === 'yes'){
    
    // Excluir o projeto da base de dados
    $database->execute_non_query("DELETE FROM projetos WHERE id = :id", [':id' => $id]);
    header('Location: gerir_projetos.php');
exit();
}




?>

<div class="container">
    <h2>Projeto</h2>
    <div class="row ">
        <div class="col-md-4  ">
            <div class="card ">
                <img src="uploads/<?= htmlspecialchars($projeto->fotografia) ?>" class="card-img-top" alt="<?= htmlspecialchars($projeto->titulo) ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($projeto->titulo) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($projeto->descricao) ?></p>
                    <p class="card-text"><strong>Tecnologia:</strong> <?= htmlspecialchars($projeto->tecnologia) ?></p>
                    <p class="card-text"><strong>Data de Início:</strong> <?= htmlspecialchars($projeto->data_inicio) ?></p>
                    <p class="card-text"><strong>Data de Fim:</strong> <?= htmlspecialchars($projeto->data_fim) ?></p>
                    <a href="gerir_projetos.php" class="btn btn-outline-dark yes-no-width">Não</a>
                    <a href="excluir_projeto.php?id=<?= htmlspecialchars($id) ?>&delete=yes" class="btn btn-outline-danger yes-no-width">Sim</a>

                </div>
            </div>
        </div>
    </div>
<?php
require_once('footer.php');
?>