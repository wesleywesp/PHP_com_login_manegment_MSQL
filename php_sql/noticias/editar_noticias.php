<?php
use sys4soft\Database;
require_once('header.php');

// Verificar se o 'id' foi enviado via GET
if (empty($_GET['id'])) {
    header('Location: ../login.html');
    exit();
}

require_once('../libraries/config.php');
require_once('../libraries/Database.php');

$id = $_GET['id'];
$erro = null;
$database = new Database(MYSQL_CONFIG);

// Pegar os dados da notícia
$parames = [
    ':id' => $id,
];
$results = $database->execute_query("SELECT * FROM noticias WHERE id = :id", $parames);
$noticias = $results->results[0] ?? null;

// Verificar se existe um POST (formulário foi submetido)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['text_conteudo'];

    // Atualizar a notícia atual
    $parames = [
        ':id' => $id,
        ':titulo' => $titulo,
        ':conteudo' => $conteudo,
    ];

    $sql = "UPDATE noticias
            SET titulo = :titulo, conteudo = :conteudo, data_publicacao = NOW()
            WHERE id = :id";

    // Executar a consulta de atualização
    $database->execute_non_query($sql, $parames);

    header('Location: gerir_noticias.php');
    exit();
}
?>

<div class="row justify-content-center" style="padding-top:50px">
    <div class="col-sm-8 col-md-6 col-10">

        <div class="card p-4">

            <form action="editar_noticias.php?id=<?= htmlspecialchars($id) ?>" method="post">
                <p class="text-center"><strong>EDITAR NOTICIAS</strong></p>
                <div class="mb-3">
                    <label for="titulo" class="form-label">TITULO</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" minlength="3" maxlength="50" required value="<?= htmlspecialchars($noticias->titulo) ?>">
                </div>
                <div class="mb-3">
                    <label for="text_conteudo" class="form-label">conteudo</label>
                    <textarea name="text_conteudo" id="text_conteudo" class="form-control" required><?= htmlspecialchars($noticias->conteudo) ?></textarea>
                </div>
                <div class="text-center">
                    <a href="gerir_noticias.php" class="btn btn-outline-dark">Cancelar</a>
                    <input type="submit" value="Atualizar" class="btn btn-outline-primary">
                </div>
            </form>

        </div>

        <!-- Mensagem de erro -->
        <?php if (!empty($erro)): ?>
        <div class="mt-3 alert alert-danger p-2 text-center">
            <?= htmlspecialchars($erro) ?>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php require_once('footer.php');?>
