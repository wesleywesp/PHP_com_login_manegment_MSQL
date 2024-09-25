<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

if ($_SESSION['funcao'] == 'admin') {
    echo "<h1>Área Administrativa</h1>";
    echo "<a href='UTILIZADORES/gerir_utilizadores.php'>Gerir Utilizadores</a><br>";
    echo "<a href='projetos/gerir_projetos.php'>Gerir Projetos</a><br>";
    echo "<a href='noticias/gerir_noticias.php'>Gerir Notícias</a><br>";
    echo  "<a href='consultas/admin/table_consulta.php'>Table Consulta</a><br>";

} else {
    $id=$_SESSION['user_id'];
    echo "<h1>Área do Utilizador</h1>";
    echo "<a href= 'consultas/user/minhas_marcacoes.php?id=$id'>Minha Conta</a><br>";
}

echo "<br><a href='logout.php'>Logout</a>";
