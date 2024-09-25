<?php
use sys4soft\Database;
require_once('header.php');
require_once('../libraries/Database.php');
require_once('../libraries/config.php');
$database = new Database(MYSQL_CONFIG);

// Obter todos os projetos ativos
$projetos = $database->execute_query("SELECT * FROM projetos WHERE status = 'ativo'")->results;
?>

<div class="container" style="padding-bottom:50px;">
    <h2>Portfólio</h2>
    <div class="row">
        <?php foreach ($projetos as $projeto): ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="uploads/<?= htmlspecialchars($projeto->fotografia) ?>" class="card-img-top" alt="<?= htmlspecialchars($projeto->titulo) ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($projeto->titulo) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($projeto->descricao) ?></p>
                    <p class="card-text"><strong>Tecnologia:</strong> <?= htmlspecialchars($projeto->tecnologia) ?></p>
                    <p class="card-text"><strong>Data de Início:</strong> <?= htmlspecialchars($projeto->data_inicio) ?></p>
                    <p class="card-text"><strong>Data de Fim:</strong> <?= htmlspecialchars($projeto->data_fim) ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php require('footer.php');?>