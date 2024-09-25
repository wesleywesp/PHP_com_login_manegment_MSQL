<?php
use sys4soft\Database;
require_once('header.php');

// Verificar se o 'id' foi enviado via GET e é válido
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: gerir_noticias.php');
    exit();
}

require_once('../libraries/config.php');
require_once('../libraries/Database.php');

$id = $_GET['id'];
$database = new Database(MYSQL_CONFIG);
$parames = [':id' => $id];

// Verificar se o contato existe
$results = $database->execute_query("SELECT * FROM noticias WHERE id = :id", $parames);
$noticia = $results->results[0] ?? null;

if (!$noticia) {
    // Se o contato não for encontrado, redireciona ou exibe uma mensagem de erro
    header('Location: gerir_noticias.php');
    exit();
}

// Verificar se a solicitação de exclusão foi enviada
if (isset($_GET['delete']) && $_GET['delete'] === 'yes') {
    $database->execute_non_query("DELETE FROM noticias WHERE id = :id", $parames);
    header('Location: gerir_noticias.php');
    exit();
}
?>

<div class="row">
    <div class="col text-center" style="padding-top:100px">
        <h3>Deseja eliminar a seguinte noticia?</h3>

        <div class="col text-center">
            <div class="card mb-4">
                <div class="card-body">
                <h5 class="card-title"><strong><?= htmlspecialchars($noticia->titulo) ?></strong></p>
                <p class="card-text"><?= htmlspecialchars($noticia->conteudo) ?></p>
                <p class="card-text"><?= htmlspecialchars($noticia->data_publicacao) ?></p>
        <a href="gerir_noticias.php" class="btn btn-outline-dark yes-no-width">Não</a>
        <a href="eliminar_noticias.php?id=<?= htmlspecialchars($id) ?>&delete=yes" class="btn btn-outline-danger yes-no-width">Sim</a>
           
            </div>
        </div>

    </div>
</div>
<?php require_once('footer.php');?>



