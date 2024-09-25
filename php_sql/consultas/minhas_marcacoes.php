<?php
require_once('header.php');
use sys4soft\Database;
require_once('../libraries/config.php');
require_once('../libraries/Database.php');

// Verifica se o usuário está logado e é administrador

$consultas = null;
$total_consultas = 0;
$database = new Database(MYSQL_CONFIG);

// Verifica se o ID foi passado via GET
if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    
    // Executa a consulta usando um parâmetro preparado
    $params = [
        ':id' => $id,
    ];
    $resultados = $database->execute_query(
        "SELECT Consultas.id, Utilizadores.nome, Consultas.observacoes, Consultas.data_consulta, Consultas.created_at 
        FROM Consultas 
        JOIN Utilizadores ON Consultas.id_cliente = Utilizadores.id 
        WHERE Consultas.id_cliente = :id",
        $params
    );
} else {
    echo "<p>ID não foi fornecido.</p>";
    exit();
}

if ($resultados->status === 'success') {
    $consultas = $resultados->results;
    $total_consultas = $resultados->affected_rows;
} else {
    // Tratamento de erro
    echo "<p>Erro ao executar a consulta: {$resultados->message}</p>";
}
?>
<h2>Minhas Consultas</h2>
    <div class="col text-end">
        <a href="marcar_consulta_frm.php" class="btn btn-outline-primary">Marcar Consulta</a>
    </div>
</div>

<!-- Exibe a tabela de consultas -->
<div class="row">
    <div class="col">
        <?php if ($total_consultas == 0): ?>
            <p class="text-center opacity-75 mt-3">Não foram encontrados consultas registados.</p>
        <?php else: ?>
            <table class="table table-sm table-striped table-bordered">
                <thead class="bg-dark text-white">
                    <tr>
                        <th width="30%">Nome</th>
                        <th width="30%">Observação</th>
                        <th width="20%">Data da Consulta</th>
                        <th width="10%">Editar</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($consultas as $consulta): ?>
                    <tr>
                        <td><?= htmlspecialchars($consulta->nome) ?></td>
                        <td><?= htmlspecialchars($consulta->observacoes) ?></td>
                        <td><?= htmlspecialchars($consulta->data_consulta) ?></td>
                        <td class="text-center">
                            <a href="editar_consulta.php?id=<?= htmlspecialchars($consulta->id) ?>" class="btn btn-warning">Editar</a>
                        </td>                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table> 
            
            <div class="row">
                <div class="col">
                    <p>Total: <strong><?= htmlspecialchars($total_consultas) ?></strong></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once('footer.php');
?>
