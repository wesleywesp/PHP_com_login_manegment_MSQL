<?php
session_name('valida');
session_start();

use sys4soft\Database;

require_once('libraries/Database.php');
require_once('libraries/config.php');

$database = new Database(MYSQL_CONFIG);
if (!isset($_SESSION['user_id'])) {
    header("Location: login_index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controlo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Inicio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($_SESSION['funcao'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href='utilizadores/gerir_utilizadores.php'>Gerir Utilizadores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href='projetos/gerir_projetos.php'>Gerir Projetos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href='noticias/gerir_noticias.php'>Gerir Notícias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href='consultas/table_consulta.php'>Gerir Consulta</a>
                        </li>

                    <?php else:
                        $id = $_SESSION['user_id'];
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href='consultas/minhas_marcacoes.php?id=<?= $id ?>'>Minhas
                                consultas</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href='logout.php'>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--consultas admin-->
    <div class="container mt-4" style="padding-top: 50px;">
        <?php if ($_SESSION['funcao'] == 'admin' && $database->execute_query("SELECT * FROM consultas WHERE data_consulta BETWEEN NOW() AND NOW() + INTERVAL 72 HOUR")->affected_rows != 0):
            ?>
            <div class="container">
                <?php
                $consultas = $database->execute_query("SELECT 
    Consultas.id, 
    Utilizadores.nome, 
    Consultas.observacoes, 
    Consultas.data_consulta, 
    Consultas.created_at 
FROM 
    Consultas 
JOIN 
    Utilizadores 
ON 
    Consultas.id_cliente = Utilizadores.id
WHERE 
    Consultas.data_consulta BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 72 HOUR)
ORDER BY 
    Consultas.data_consulta ASC
")->results; ?>
                <h2>Consultas Ativos</h2>
                <div class="row">
                    <?php foreach ($consultas as $consulta): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($consulta->nome) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($consulta->observacoes) ?></p>
                                    <p class="card-text"><?= htmlspecialchars($consulta->data_consulta) ?></p>
                                    <a href="consultas/editar_consulta.php?id=<?= htmlspecialchars($consulta->id, ENT_QUOTES, 'UTF-8') ?>"
                                        class="btn btn-warning">Editar</a>
                                    <a href="consultas/eliminar_consulta.php?id=<?= htmlspecialchars($consulta->id, ENT_QUOTES, 'UTF-8') ?>"
                                        class="btn btn-danger">Eliminar</a>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                <?php endif; ?>
    </div>
    <?php 
    $id = $_SESSION['user_id']; 
    $consulta_query = "SELECT * FROM consultas WHERE data_consulta BETWEEN NOW() AND NOW() + INTERVAL 72 HOUR AND id_cliente = $id";
    
    if ($_SESSION['funcao'] != 'admin' && $database->execute_query($consulta_query)->affected_rows != 0):
        $consultas = $database->execute_query($consulta_query)->results;
    ?>                     <!--consultas user-->
    <div class="container mt-4" style="padding-top: 50px;">
    <div class="col text-end">
<a href="consultas/marcar_consulta_frm.php" class="btn btn-outline-primary">Marcar Consulta</a>
</div>
        <div class="container text-center">
            <h2>Consultas Ativas</h2>
            <div class="row">
                <?php foreach ($consultas as $consulta): ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($consulta->observacoes) ?></h5>

                                <p class="card-text"><?= htmlspecialchars($consulta->data_consulta) ?></p>
                                <a href="consultas/editar_consulta.php?id=<?= htmlspecialchars($consulta->id, ENT_QUOTES, 'UTF-8') ?>"
                                    class="btn btn-warning">Editar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <hr>
            </div>
        </div>
    <?php endif; ?>
</div>
            <!-- Conteúdo projetos -->
            <div class="container mt-4" style="padding-top: 50px;">
                <?php if ($_SESSION['funcao'] == 'admin'): ?>
                    <div class="container">
                        <?php $projetos = $database->execute_query("SELECT * FROM projetos WHERE status = 'ativo'")->results;
                        ?>
                        <h2>Projetos Ativos</h2>
                        <div class="row">
                            <?php foreach ($projetos as $projeto): ?>
                                <div class="col-md-4">
                                    <div class="card mb-4">
                                        <img src="projetos/uploads/<?= htmlspecialchars($projeto->fotografia) ?>"
                                            class="card-img-top" alt="<?= htmlspecialchars($projeto->titulo) ?>">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($projeto->titulo) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($projeto->descricao) ?></p>
                                            <p class="card-text"><strong>Tecnologia:</strong>
                                                <?= htmlspecialchars($projeto->tecnologia) ?></p>
                                            <p class="card-text"><strong>Data de Início:</strong>
                                                <?= htmlspecialchars($projeto->data_inicio) ?></p>
                                            <p class="card-text"><strong>Data de Fim:</strong>
                                                <?= htmlspecialchars($projeto->data_fim) ?></p>
                                            <a href="projetos/editar_projetos.php?id=<?= htmlspecialchars($projeto->id, ENT_QUOTES, 'UTF-8') ?>"
                                                class="btn btn-warning">Editar</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <hr>
                        <?php endif; ?>
                        <div class="container mt-4" style="padding-bottom: 100px">
                            <div class="container">
                                <?php $noticias = $database->execute_query("SELECT * FROM noticias")->results ?>

                                <h2>Prêmios</h2>
                                <div class="row">
                                    <?php foreach ($noticias as $noticia): ?>
                                        <div class="col-md-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?= htmlspecialchars($noticia->titulo) ?></h5>
                                                    <p class="card-text"><?= htmlspecialchars($noticia->conteudo) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer -->
                <div class="container-fluid fixed-bottom ">
                    <div class="row">
                        <div class="col bg-dark text-white p-2 text-center">
                            <p>Dashboard <?= date('Y') ?></p>
                            <P>Wesley &copy; Pinto</P>
                        </div>
                    </div>
                </div>

                <!-- Bootstrap JS and dependencies -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>