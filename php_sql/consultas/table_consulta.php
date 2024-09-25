<?php
use sys4soft\Database;
require_once('header.php');
require_once('../libraries/config.php');
require_once('../libraries/Database.php');

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['user_id']) || !isset($_SESSION['funcao']) || $_SESSION['funcao'] != 'admin') {
    header("Location: ../login_index.php");
    exit();
}

// Geração e validação do token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$consultas = null;
$total_consultas = 0;
$search = null;
$database = new Database(MYSQL_CONFIG);

// Verifica se houve um pedido de pesquisa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificação do token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token inválido.');
    }

    // Procura resultados
    $search = filter_input(INPUT_POST, 'text_search');
    $params = [
        ':search' => '%' . $search . '%',
    ];
    $resultados = $database->execute_query(
        "SELECT consultas.*, utilizadores.nome 
         FROM consultas 
         JOIN utilizadores ON consultas.id_cliente = utilizadores.id 
         WHERE utilizadores.nome LIKE :search 
         OR consultas.data_consulta LIKE :search 
         ORDER BY consultas.id DESC",
        $params
    );
} else {
    $resultados = $database->execute_query("SELECT Consultas.id, Utilizadores.nome, Consultas.observacoes, Consultas.data_consulta, Consultas.created_at 
    FROM Consultas 
    JOIN Utilizadores ON Consultas.id_cliente = Utilizadores.id");
}

if ($resultados->status === 'success') {
    $consultas = $resultados->results;
    $total_consultas = $resultados->affected_rows;
} else {
    // Tratamento de erro
    echo "<p>Erro ao executar a consulta:</p>";
}
?>
<div class="container" style="padding-bottom:50px;">
    <h2>Gerir Consultas</h2>
    <div class="row align-items-center mb-3">
        <div class="col">
            <form action="table_consulta.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <div class="row">
                    <div class="col-auto">
                        <input type="text" class="form-control" name="text_search" id="text_search" minlength="3" maxlength="20" required>
                    </div>
                    <div class="col-auto">
                        <input type="submit" class="btn btn-outline-dark" value="Procurar">
                    </div>
                    <div class="col-auto">
                        <a href="table_consulta.php" class="btn btn-outline-dark">Ver tudo</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <?php if ($total_consultas == 0): ?>
                <p class="text-center opacity-75 mt-3">Não foram encontrados consultas registados.</p>
            <?php else: ?>
                <table class="table table-sm table-striped table-bordered">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th width="10%">ID</th>
                            <th width="30%">Nome</th>
                            <th width="30%">observação</th>
                            <th width="20%">Data da Consulta</th>
                            <th width="20%">Data da criação</th>
                            <th width="5%">Editar</th>
                            <th width="5%">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consultas as $consulta): ?>
                        <tr>
                            <td><?= htmlspecialchars($consulta->id, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($consulta->nome, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($consulta->observacoes, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($consulta->data_consulta, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($consulta->created_at, ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-center">
                                <a href="editar_consulta.php?id=<?= htmlspecialchars($consulta->id, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-warning">Editar</a>
                            </td>
                            <td class="text-center">
                                <a href="eliminar_consulta.php?id=<?= htmlspecialchars($consulta->id, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table> 
                
                <div class="row">
                    <div class="col">
                        <p>Total: <strong><?= htmlspecialchars($total_consultas, ENT_QUOTES, 'UTF-8') ?></strong></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once('footer.php');
?>

