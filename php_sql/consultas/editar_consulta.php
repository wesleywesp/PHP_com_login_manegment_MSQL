<?php

require_once('header.php');
use sys4soft\Database;
require_once('../libraries/config.php');
require_once('../libraries/Database.php');

// Verificar se o usuário está logado e se o ID da consulta foi fornecido
if (!isset($_SESSION['user_id']) || empty($_GET['id'])) {
    header('Location: ../login_index.php');
    exit();
}

$database = new Database(MYSQL_CONFIG);
$consulta_id = $_GET['id'];
$id_utilizador = $_SESSION['user_id'];

// Parâmetros para a consulta
$parames_consulta = [
    ':id' => $consulta_id,
];

// Recuperar os dados da consulta e do usuário associado
$consulta = $database->execute_query(
    "SELECT 
        Consultas.*, 
        Utilizadores.nome 
    FROM 
        Consultas 
    JOIN 
        Utilizadores 
    ON 
        Consultas.id_cliente = Utilizadores.id 
    WHERE 
        Consultas.id = :id",
    $parames_consulta
)->results[0];

// Verificar se a consulta foi encontrada
if (!$consulta) {
    die("Consulta não encontrada.");
}

// Verificar se a consulta pertence ao usuário e se ainda está no prazo de edição (72 horas)
$parames_verificacao = [
    ':consulta_id' => $consulta_id,
    ':id' => $id_utilizador,
];

$resultados = $database->execute_query(
    "SELECT * FROM Consultas WHERE id = :consulta_id AND id_cliente = :id AND TIMESTAMPDIFF(HOUR, NOW(), data_consulta) <= 72",
    $parames_verificacao
);

if ($resultados->affected_rows == 1 && $_SESSION['funcao'] != 'admin') {
    echo '<span class="text-danger"><small><i> Não é possível alterar a consulta porque está dentro do prazo de 72 horas. </i></small></span>
     <br><a href="minhas_marcacoes.php?id='. $id_utilizador. '"class="btn btn-outline-primary">Marcar Consulta</a>';
    die();
}

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proteção contra CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token inválido.");
    }

    // Processo de atualização da consulta
    $data_consulta = filter_input(INPUT_POST, 'data_consulta');
    $time = filter_input(INPUT_POST, 'time');
    $observacoes = filter_input(INPUT_POST, 'observacoes');

    // Inicializar variáveis de erro
    $error = [
        'time' => '',
        'data' => ''
    ];

    // Validar horário da consulta
    if ($time < "08:00" || $time > "19:00") {
        $error['time'] = 'Não fazemos consultas fora do horário das 08:00 às 19:00.';
    }

    // Validar se a data é um dia útil (não final de semana)
    $dia_semana = date('N', strtotime($data_consulta)); // N = 1 (segunda-feira) a 7 (domingo)
    if ($dia_semana >= 6) { // 6 = Sábado, 7 = Domingo
        $error['data'] = 'Consultas não são permitidas em finais de semana.';
    }

    // Se houver erros, exibir os erros e não processar a atualização
    if (!empty($error['time']) || !empty($error['data'])) {
        $data = new DateTime($consulta->data_consulta);
        $date_value = $data->format('Y-m-d'); // Extrai a data
        $time_value = $data->format('H:i');   // Extrai a hora
        ?>
        <h2>Editar Consulta</h2>
        <div class="row align-items-start">
            <div class="card p-4">
                <form action="editar_consulta.php?id=<?= htmlspecialchars($consulta_id, ENT_QUOTES, 'UTF-8') ?>" method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Nome</label>
                        <input type="text" name="titulo" disabled id="titulo" class="form-control" minlength="3" maxlength="50" required value="<?= htmlspecialchars($consulta->nome, ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="data_consulta" class="form-label">Data da Consulta</label>
                        <input type="date" name="data_consulta" id="data_consulta" class="form-control" value="<?= htmlspecialchars($date_value, ENT_QUOTES, 'UTF-8') ?>" required>
                        <?php if (!empty($error['data'])): ?>
                            <div class="alert alert-danger p-2 text-center">
                                <?= htmlspecialchars($error['data'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="time" class="form-label">Hora:</label>
                        <input type="time" name="time" id="time" class="form-control" value="<?= htmlspecialchars($time_value, ENT_QUOTES, 'UTF-8') ?>" required>
                        <?php if (!empty($error['time'])): ?>
                            <div class="alert alert-danger p-2 text-center">
                                <?= htmlspecialchars($error['time'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="consulta_id" value="<?= htmlspecialchars($consulta->id, ENT_QUOTES, 'UTF-8') ?>">
                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Conteúdo</label>
                        <textarea name="observacoes" id="observacoes" class="form-control" required><?= htmlspecialchars($consulta->observacoes, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                    <div class="text-center">
                        <a href="../dashboard.php" class="btn btn-outline-dark">Cancelar</a>
                        <input type="submit" value="Atualizar" class="btn btn-outline-primary">
                    </div>
                </form>
            </div>
        </div>
        <?php
        require_once('footer.php');
        exit(); // Evitar que o script continue após exibir os erros
    }

    // Concatenar data e hora corretamente
    $data_hora_consulta = $data_consulta . ' ' . $time . ':00';  // Formato "YYYY-MM-DD HH:MM:00"

    // Parâmetros para atualização
    $parames_atualizacao = [
        ':consulta_id' => $consulta_id,
        ':data_consulta' => $data_hora_consulta,
        ':observacoes' => $observacoes,
    ];

    // Executar a atualização
    $resultados = $database->execute_non_query(
        "UPDATE Consultas SET data_consulta = :data_consulta, observacoes = :observacoes 
         WHERE id = :consulta_id",
        $parames_atualizacao
    );

    // Verificar se a atualização foi bem-sucedida
    if ($resultados) {
        if($_SESSION['funcao'] != 'admin'){
        header('Location:../dashboard.php');
        exit();
        }else{
            header('Location:table_consulta.php');
            exit();

        }
    } else {
        die("Erro ao atualizar a consulta.");
    }
}

// Geração do token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>
<?php
$data = new DateTime($consulta->data_consulta);
$date_value = $data->format('Y-m-d'); // Extrai a data
$time_value = $data->format('H:i');   // Extrai a hora
?>
    <h2>Editar Consulta</h2>
    <div class="row align-items-start">
        <div class="card p-4">
            <form action="editar_consulta.php?id=<?= htmlspecialchars($consulta_id, ENT_QUOTES, 'UTF-8') ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Nome</label>
                    <input type="text" name="titulo" disabled id="titulo" class="form-control" minlength="3" maxlength="50" required value="<?= htmlspecialchars($consulta->nome, ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="mb-3">
                    <label for="data_consulta" class="form-label">Data da Consulta</label>
                    <input type="date" name="data_consulta" id="data_consulta" class="form-control" value="<?= htmlspecialchars($date_value, ENT_QUOTES, 'UTF-8') ?>" required>
                    <?php if (!empty($error['data'])): ?>
                        <div class="alert alert-danger p-2 text-center">
                            <?= htmlspecialchars($error['data'], ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Hora:</label>
                    <input type="time" name="time" id="time" class="form-control" value="<?= htmlspecialchars($time_value, ENT_QUOTES, 'UTF-8') ?>" required>
                    <?php if (!empty($error['time'])): ?>
                        <div class="alert alert-danger p-2 text-center">
                            <?= htmlspecialchars($error['time'], ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="consulta_id" value="<?= htmlspecialchars($consulta->id, ENT_QUOTES, 'UTF-8') ?>">
                <div class="mb-3">
                    <label for="observacoes" class="form-label">Conteúdo</label>
                    <textarea name="observacoes" id="observacoes" class="form-control" required><?= htmlspecialchars($consulta->observacoes, ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
                <div class="text-center">
                    <a href="../dashboard.php" class="btn btn-outline-dark">Cancelar</a>
                    <input type="submit" value="Atualizar" class="btn btn-outline-primary">
                </div>
            </form>
        </div>
    </div>

<?php
require_once('footer.php');
?>
