<!doctype html>
<html lang="pt">
<?php
use sys4soft\Database;
require_once('php_sql/libraries/Database.php');
require_once('php_sql/libraries/config.php');
$database = new Database(MYSQL_CONFIG);
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://kit.fontawesome.com/ab2a78b3f0.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./style/index.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script type="text/javascript" src="./javascript/galeria.js" defer></script>
    <script type="text/javascript" src="./javascript/rss.js" defer></script>
    <title>Principal</title>
</head>

<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-sm fixed-top navbar-dark bg-dark">
            <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions"
                aria-controls="offcanvasWithBothOptions" onclick="noticiasRss()">
                <i class="fa-solid fa-square-rss fa-2xl" style="color: #FFD43B;"></i>
            </button>
            <button class="navbar-toggler navbar-nav" style="border: none;">
                <a href="#home" class="nav-link" style="border: none;"><i class="fa-solid fa-code"></i></a>
            </button>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gale">Galeria</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./orcamento.html#contactos_maps">Contactos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./orcamento.html#orcamento">Orçamento</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a class="nav-link "href="./php_sql/login_index.php">Login</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- Menu lateral -->
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
        aria-labelledby="offcanvasWithBothOptionsLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Notícias RSS
                <i class="fa-solid fa-square-rss fa-lg" style="color: #FFD43B;"></i>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div id="notic"></div>
        </div>
    </div>

    <!-- Home -->
    <section class="container-fluid" id="home"
        style="background-color: #21303A; min-height: 80vh; text-align: center; padding-top: 20%;">
        <div class="row justify-content-center">
            <div class="col-8" style="background-color: #21303A;">
                <img src="./style/weasp.png" alt="logotipo da empresa"><br>
                <p
                    style="color: white; padding-left: 10%; padding-right: 10%; text-align: center; padding-top: 5%; line-height: 1.5;">
                    Nós somos uma startup de tecnologia que desenvolve soluções inovadoras para facilitar a vida das
                    pessoas.
                    Com uma equipe de profissionais altamente qualificados e apaixonados por tecnologia, buscamos
                    constantemente novas maneiras de simplificar processos e otimizar resultados. Nossos produtos são
                    focados em proporcionar praticidade, segurança e eficiência para nossos clientes, atendendo às suas
                    necessidades de forma personalizada e assertiva.
                    Estamos comprometidos em oferecer o melhor serviço e experiência aos nossos usuários, sempre com
                    transparência e ética em nossas práticas comerciais.
                </p>
            </div>
        </div>

        <div class="container mt-4" style="padding: 100px;">
            <div class="container">
                <?php
                $noticias = $database->execute_query("SELECT * FROM noticias")->results;
                ?>

                <h2 style="color: white;">Reconhecimentos em Portugal</h2>
                <div class="row">
                    <?php foreach ($noticias as $noticia): ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body" style="background-color: #21303A">
                                    <h5 class="card-title" style="color: white;"><?= htmlspecialchars($noticia->titulo) ?>
                                    </h5>
                                    <p class="card-text" style="color: white;"><?= htmlspecialchars($noticia->conteudo) ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Galeria -->
    <div id="gale" style="text-align: center; padding-top: 10%;">
        <h1>Álbum de Imagens do Unsplash</h1>
    </div>
    <section>
        <div id="galeria" class="gal"></div>
    </section>

    <hr>

    <!-- Footer -->
    <footer>
        <div class="container-fluid justify-content-end emoji">
            <a href="https://www.tiktok.com/@weasp_dev" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
            <a href="https://www.youtube.com/@weasp_dev" target="_blank"><i class="fa-brands fa-youtube"></i></a>
            <a href="https://www.facebook.com/weasp_dev/" target="_blank"><i class="fa-brands fa-facebook"></i></a>
            <a href="https://www.instagram.com/weasp_dev/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
            <p style="font-size: small; color: rgba(9, 8, 8, 0.5);">Powered by <span
                    style="text-decoration: underline;">Wesley A. Pinto</span></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>