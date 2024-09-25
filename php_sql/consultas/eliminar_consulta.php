<?php
use sys4soft\Database;
require_once('header.php');
require_once('../libraries/config.php');
require_once('../libraries/Database.php');

if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: table_consulta.php');
    exit();
}

$id = $_GET['id'];
$database = new Database(MYSQL_CONFIG);
$parames = [':id' => $id];

// Verificar se o contato existe
$results = $database->execute_query("SELECT * FROM consultas WHERE id = :id", $parames);
$consultas = $results->results[0] ?? null;
$consulta_1 = $database->execute_query(
    "SELECT Consultas.id, Utilizadores.nome 
     FROM Consultas 
     JOIN Utilizadores ON Consultas.id_cliente = Utilizadores.id 
     WHERE Consultas.id = :id",
    $parames
)->results[0];

if (!$consultas) {
    // Se a contato não for encontrado, redireciona ou exibe uma mensagem de erro
    header('Location: table_consulta.php');
    exit();
}

// Verificar se a solicitação de exclusão foi enviada
if (isset($_GET['delete']) && $_GET['delete'] === 'yes') {
    $database->execute_non_query("DELETE FROM consultas WHERE id = :id", $parames);
    header('Location: table_consulta.php');
    exit();
}


?>

<div class="row">
    <div class="col text-center">
        <h3>Deseja eliminar a seguinte consulta?</h3>

        <div class="my-4">
            <div>
            <span class="me-5">id: <strong><?= htmlspecialchars($consultas->id) ?></strong></span>
            <span class="me-5">Nome: <strong><?= htmlspecialchars($consulta_1->nome) ?></strong></span>
                <span class="me-5">Observações: <strong><?= htmlspecialchars($consultas->observacoes) ?></strong></span>
                <span class="me-5">Data da consulta: <strong><?= htmlspecialchars($consultas->data_consulta) ?></strong></span>
            </div>
        </div>

        <a href="table_consulta.php" class="btn btn-outline-dark yes-no-width">Não</a>
        <a href="eliminar_consulta.php?id=<?= htmlspecialchars($consultas->id) ?>&delete=yes" class="btn btn-outline-danger yes-no-width">Sim</a>
    </div>
</div>

<?php
require_once('footer.php');
?>

