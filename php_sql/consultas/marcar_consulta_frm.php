<?php

require_once('header.php');

use sys4soft\Database;
require_once('../libraries/config.php');
require_once('../libraries/Database.php');
// Verificar se há dados na sessão para mostrar os erros e valores preenchidos anteriormente
if (isset($_SESSION['inputs'])) {
    $inputs = $_SESSION['inputs'];
    unset($_SESSION['inputs']);
}
// Gerar um token CSRF e armazená-lo na sessão
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
require_once('../libraries/funcoes.php');
?>

<h2>Marcar Consulta</h2>
<div class="row align-items-start">
    <div class="card p-4">
        <form action="marcar_consulta.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <div class="mb-3">
                <label for="data_consulta" class="form-label">Data da Consulta</label>
                <input type="date" name="data_consulta" id="data_consulta" class="form-control" required>
                <?= show_error('data_consulta') ?>

            </div>
            <div class="mb-3">
                <label for="time" class="form-label">Hora:</label>
                <input type="time" name="time" id="time" class="form-control" required>
                <?= show_error('time') ?>
            </div>
            <input type="hidden" name="consulta_id">
            <div class="mb-3">
                <label for="observacoes" class="form-label">Conteúdo</label>
                <textarea name="observacoes" id="observacoes" class="form-control" required value="<?= show_value('observacoes')?>"></textarea>
                <?= show_error('observacoes') ?>
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
