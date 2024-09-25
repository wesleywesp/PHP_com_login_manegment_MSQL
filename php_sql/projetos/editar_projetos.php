<?php
use sys4soft\Database;
require_once('header.php');

if (empty($_GET['id'])) {
    header('Location: ../login.html');
    exit();
}

require_once('../libraries/config.php');
require_once('../libraries/Database.php');

$id = $_GET['id'];
$erro = null;
$database = new Database(MYSQL_CONFIG);

$parames = [':id' => $id];
$results = $database->execute_query("SELECT * FROM projetos WHERE id = :id", $parames);
$projetos = $results->results[0] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $tecnologia = $_POST['tecnologia'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $fotografia = $_FILES['fotografia'];

    if ($fotografia['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads';
        $uploadFile = $uploadDir . basename($fotografia['name']);
        if (move_uploaded_file($fotografia['tmp_name'], $uploadFile)) {
            $fotografia = $fotografia['name'];
        } else {
            $erro = "Erro ao mover o arquivo da fotografia.";
        }
    } else {
        $fotografia = $projetos->fotografia;
    }

    $parames = [
        ':id' => $id,
        ':titulo' => $titulo,
        ':descricao' => $descricao,
        ':tecnologia' => $tecnologia,
        ':data_inicio' => $data_inicio,
        ':data_fim' => $data_fim,
        ':fotografia' => $fotografia
    ];

    $sql = "UPDATE projetos 
            SET titulo = :titulo, descricao = :descricao, tecnologia = :tecnologia, 
            data_inicio = :data_inicio, data_fim = :data_fim, fotografia = :fotografia
            WHERE id = :id";

    $affectedRows = $database->execute_non_query($sql, $parames);
    if ($affectedRows === 0) {
        $erro = "Nenhuma mudança foi feita ou houve um erro ao atualizar o projeto.";
    } else {
        header('Location: gerir_projetos.php');
        exit();
    }
}
?>

<div class="container">
<div class="row align-items-center mb-3">
        <div class="card p-4">
    <h2>Editar Projeto</h2>
    <form action="editar_projetos.php?id=<?= htmlspecialchars($id) ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required value="<?= htmlspecialchars($projetos->titulo) ?>">
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" required><?= htmlspecialchars($projetos->descricao) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="tecnologia" class="form-label">Tecnologia</label>
            <input type="text" name="tecnologia" id="tecnologia" class="form-control" required value="<?= htmlspecialchars($projetos->tecnologia) ?>">
        </div>
        <div class="mb-3">
            <label for="data_inicio" class="form-label">Data de Início</label>
            <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="<?= htmlspecialchars($projetos->data_inicio) ?>">
        </div>
        <div class="mb-3">
            <label for="data_fim" class="form-label">Data de Fim</label>
            <input type="date" name="data_fim" id="data_fim" class="form-control"value="<?= htmlspecialchars($projetos->data_fim) ?>">
        </div>
        <div class="mb-3">
            <label for="fotografia" class="form-label">Fotografia</label>
            <input type="file" name="fotografia" id="fotografia" class="form-control">
        </div>
        <div class="text-center">
            <a href="gerir_projetos.php" class="btn btn-outline-dark">Cancelar</a>
            <input type="submit" value="Guardar" class="btn btn-primary">
        </div>
    </form>
    <?php if ($erro): ?>
    <div class="alert alert-danger mt-3"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
</div>
<?php require_once('footer.php'); ?>

