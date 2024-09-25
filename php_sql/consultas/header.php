<?php 
session_name('valida');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,300;0,700;1,400&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS & custom CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">    <link rel="stylesheet" href="assets/app.css">
    <!-- favicon -->
    <link rel="shortcut icon" href="weasp.png" type="image/png">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="../dashboard.php">Inicio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($_SESSION['funcao'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href='../utilizadores/gerir_utilizadores.php'>Gerir Utilizadores</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href='../projetos/gerir_projetos.php'>Gerir Projetos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href='../noticias/gerir_noticias.php'>Gerir Notícias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href='table_consulta.php'>Gerir Consulta</a>
                        </li>

                    <?php else:
                        $id = $_SESSION['user_id'];
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href='minhas_marcacoes.php?id=<?= $id ?>'>Minhas consultas</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href='../logout.php'>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- wrapper -->
    <div class="container" style="height:90vh ; padding-top:50px">
        <div class="row my-5">
            <div class="col">