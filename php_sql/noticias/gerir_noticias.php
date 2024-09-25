<?php
session_name('valida');
session_start();
use sys4soft\Database;
require_once('header.php');
require_once('../libraries/config.php'); // Arquivo que conecta ao banco de dados
require_once('../libraries/Database.php');

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['user_id']) || $_SESSION['funcao'] != 'admin') {
    header("Location: ../login._index.php");
    exit();
}

$noticias = null;
$total_noticias = 0;
$search = null;
$database = new Database(MYSQL_CONFIG);

// Verifica se houve um pedido de pesquisa

$resultados = $database->execute_query("SELECT * FROM noticias ORDER BY id DESC");

// Verifica se a consulta foi bem-sucedida
if ($resultados->status === 'success') {
    $noticias = $resultados->results;
    $total_noticias = $resultados->affected_rows;
} else {
    // Tratamento de erro
    echo "<p>Erro ao executar a consulta: {$resultados->message}</p>";
}
?>
<div class="container"  style="padding-bottom:50px;">
<h2>Gerir Noticias</h2>
<div class="row align-items-center mb-3">
    <div class="col text-end">
        <a href="adicionar_noticias.php" class="btn btn-outline-dark">Adicionar noticiao</a>
    </div>

<!-- Exibe a tabela de contatos -->
<div class="row">
    <div class="col">
            <!-- Com resultados -->
            <table class="table table-sm table-striped table-bordered">
                <thead class="bg-dark text-white">
                    <tr>
                        <th width="10%">Titulo</th>
                        <th width="30%">Conteudo</th>
                        <th width="30%">Data da Publicação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($noticias as $noticia): ?>
                    <tr>
                        <td><?= htmlspecialchars($noticia->titulo) ?></td>
                        <td><?= htmlspecialchars($noticia->conteudo) ?></td>
                        <td><?= htmlspecialchars($noticia->data_publicacao) ?></td>
                        <td class="text-center">
                            <a href="editar_noticias.php?id=<?= htmlspecialchars($noticia->id) ?>" class="btn btn-warning">Editar</a>
                        </td>
                        <td class="text-center">
                            <a href="eliminar_noticias.php?id=<?= htmlspecialchars($noticia->id) ?>"class="btn btn-danger">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
        </table> 
    </div>
    </div>
</div>
<?php require_once('footer.php');?>