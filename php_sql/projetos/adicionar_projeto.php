<?php
use sys4soft\Database;
require_once('header.php');
require_once('../libraries/Database.php');
require_once('../libraries/config.php');
$database = new Database(MYSQL_CONFIG);
$erro = null;

// Se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $tecnologia = $_POST['tecnologia'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $fotografia = $_FILES['fotografia'];

    // Upload da fotografia
    if ($fotografia['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'projetos//uploads/';
        $uploadFile = $uploadDir . basename($fotografia['name']);
        move_uploaded_file($fotografia['tmp_name'], $uploadFile);
    }

    // Inserir o projeto no banco de dados
    $parames = [
        ':titulo' => $titulo,
        ':descricao' => $descricao,
        ':tecnologia' => $tecnologia,
        ':data_inicio' => $data_inicio,
        ':data_fim' => $data_fim,
        ':fotografia' => $fotografia['name'] ?? null,
    ];

    $database->execute_non_query(
        "INSERT INTO projetos (titulo, descricao, tecnologia, data_inicio, data_fim, fotografia) 
         VALUES (:titulo, :descricao, :tecnologia, :data_inicio, :data_fim, :fotografia)",
        $parames
    );

    header('Location: gerir_projetos.php');
    exit();
}
?>

<div class="container">
<div class="row align-items-center mb-3">
<div class="card p-4">
    <h2>Adicionar Projeto</h2>
    <form action="adicionar_projeto.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="tecnologia" class="form-label">Tecnologia</label>
            <input type="text" name="tecnologia" id="tecnologia" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="data_inicio" class="form-label">Data de Início</label>
            <input type="date" name="data_inicio" id="data_inicio" class="form-control">
        </div>
        <div class="mb-3">
            <label for="data_fim" class="form-label">Data de Fim</label>
            <input type="date" name="data_fim" id="data_fim" class="form-control">
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
</div>
<?php require_once('footer.php');?>
