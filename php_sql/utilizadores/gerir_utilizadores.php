<?php
session_name('valida');
session_start();
use sys4soft\Database;
require_once('header.php');
require_once('../libraries/config.php'); // Arquivo que conecta ao banco de dados
require_once('../libraries/Database.php');

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['user_id']) || $_SESSION['funcao'] != 'admin') {
    header("Location: ../login_index.php");
    exit();
}

$contacts = null;
$total_contacts = 0;
$search = null;
$database = new Database(MYSQL_CONFIG);

// Verifica se houve um pedido de pesquisa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Procura resultados
    $search = $_POST['text_search'];
    $params = [
        ':search' => '%' . $search . '%',
    ];
    $resultados = $database->execute_query(
        "SELECT * FROM utilizadores WHERE nome LIKE :search OR funcao LIKE :search OR username LIKE :search OR id LIKE :search ORDER BY id DESC",
        $params
    );
} else {
    $resultados = $database->execute_query("SELECT * FROM utilizadores ORDER BY id DESC");
}

// Verifica se a consulta foi bem-sucedida
if ($resultados->status === 'success') {
    $contacts = $resultados->results;
    $total_contacts = $resultados->affected_rows;
} else {
    // Tratamento de erro
    echo "<p>Erro ao executar a consulta: {$resultados->message}</p>";
}
?>
<div class="container" style="padding-bottom:50px;">
    <h2>Gerir Noticias</h2>
    <div class="row align-items-center mb-3">
        <div class="col">
            <form action="gerir_utilizadores.php" method="post">
                <div class="row">
                    <div class="col-auto">
                        <input type="text" class="form-control" name="text_search" id="text_search" minlength="3"
                            maxlength="20" required>
                    </div>
                    <div class="col-auto">
                        <input type="submit" class="btn btn-outline-dark" value="Procurar">
                    </div>
                    <div class="col-auto">
                        <a href="gerir_utilizadores.php" class="btn btn-outline-dark">Ver tudo</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="col text-end">
            <a href="adicionar_user.php" class="btn btn-outline-dark">Adicionar Utilizadores</a>
        </div>
    </div>

    <!-- Exibe a tabela de contatos -->
    <div class="row">
        <div class="col">
            <?php if ($total_contacts == 0): ?>
                <!-- Sem resultados -->
                <p class="text-center opacity-75 mt-3">Não foram encontrados Utilizadores registados.</p>
            <?php else: ?>
                <!-- Com resultados -->
                <table class="table table-sm table-striped table-bordered">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th width="10%">ID</th>
                            <th width="30%">Nome</th>
                            <th width="30%">Username</th>
                            <th width="20%">Função</th>
                            <th width="5%">Editar</th>
                            <th width="5%">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $contact): ?>
                            <tr>
                                <td><?= htmlspecialchars($contact->id) ?></td>
                                <td><?= htmlspecialchars($contact->nome) ?></td>
                                <td><?= htmlspecialchars($contact->username) ?></td>
                                <td><?= htmlspecialchars($contact->funcao) ?></td>
                                <td class="text-center">
                                    <a href="editar_contacto.php?id=<?= htmlspecialchars($contact->id) ?>"
                                        class="btn btn-warning">Editar</a>
                                </td>
                                <td class="text-center">
                                    <a href="eliminar_contacto.php?id=<?= htmlspecialchars($contact->id) ?>"
                                        class="btn btn-danger">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Total de resultados -->
                <div class="row">
                    <div class="col">
                        <p>Total: <strong><?= htmlspecialchars($total_contacts) ?></strong></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    require_once('footer.php');
    ?>