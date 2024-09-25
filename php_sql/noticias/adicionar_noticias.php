<?php
use sys4soft\Database;

require_once('header.php');
require_once('../libraries/config.php');
require_once('../libraries/Database.php');

$erro = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database(MYSQL_CONFIG);

    // Pegar dados do POST
    $titulo = $_POST['text_titulo'];
    $conteudo = $_POST['text_conteudo'];
    $data_publicacao = $_POST['data_publicacao'];

    // Inserir a nova notícia no banco de dados
    $parames = [
        ':titulo' => $titulo,
        ':conteudo' => $conteudo,
        ':data_publicacao' => $data_publicacao, // Corrigido o nome da chave
    ];

    $database->execute_non_query(
        "INSERT INTO noticias (titulo, conteudo, data_publicacao) 
         VALUES (:titulo, :conteudo, :data_publicacao)",
        $parames
    );

    header('Location: gerir_noticias.php');
    exit();
}
?>

<div class="row justify-content-center" style="padding-top:50px">
    <div class="col-sm-8 col-md-6 col-10">
        <div class="card p-4">
            <form action="adicionar_noticias.php" method="post">
                <p class="text-center"><strong>Noticias</strong></p>
                <div class="mb-3">
                    <label for="text_titulo" class="form-label">Titulo</label>
                    <input type="text" name="text_titulo" id="text_titulo" class="form-control" minlength="3" maxlength="50" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="text_conteudo" class="form-label">Conteudo</label>
                    <textarea name="text_conteudo" id="text_conteudo" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="data_publicacao" class="form-label">Data de publicação</label>
                    <input type="date" name="data_publicacao" id="data_publicacao" class="form-control" required>
                </div>
                <div class="text-center">
                    <a href="gerir_noticias.php" class="btn btn-outline-dark">Cancelar</a>
                    <input type="submit" value="Guardar" class="btn btn-outline-primary">
                </div> <!-- Corrigido o fechamento da div -->
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

