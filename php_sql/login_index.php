<?php
// Configurar a sessão com nome específico
session_name('valida');
session_start();

$inputs = [];

// Verificar se há dados na sessão para mostrar os erros e valores preenchidos anteriormente
if (isset($_SESSION['inputs'])) {
    $inputs = $_SESSION['inputs'];
}

// Gerar um token CSRF e armazená-lo na sessão
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Função para mostrar os erros
function show_error($campo)
{
    global $inputs;
    return isset($inputs[$campo]['erro']) ? '<span class="text-danger"><small><i>' . htmlspecialchars($inputs[$campo]['erro'], ENT_QUOTES, 'UTF-8') . '</i></small></span>' : '';
}

// Função para mostrar o valor preenchido anteriormente
function show_value($campo)
{
    global $inputs;
    return isset($inputs[$campo]['value']) ? htmlspecialchars($inputs[$campo]['value'], ENT_QUOTES, 'UTF-8') : '';
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/ab2a78b3f0.js" crossorigin="anonymous"></script>
    <title>Login</title>
</head>
<body class="bg-dark">
< <div class="container-fluid">
        <?php require_once 'libraries/nav.php'; ?> <!-- Inclui a barra de navegação -->
    </div>    
    <div class="container mt-5" style="padding-top:20%">
        <div class="row justify-content-center">
            <div class="col-sm-6 card p-4 bg-light">
                <h3>LOGIN</h3>
                <hr>
                <div class="row justify-content-center">
                    <div class="col-8">
                        <form action="login.php" method="post" name="formulario">
                            <!-- Token CSRF oculto -->
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <!-- Campo de username -->
                                    <label class="form-label">Username</label>
                                    <input class="form-control" type="text" name="text_username" value="<?= show_value('text_username') ?>">
                                    <?= show_error('text_username') ?>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-12">
                                    <!-- Campo de password -->
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="text_password">
                                    <?= show_error('text_password') ?>
                                    
                                </div>
                            </div>
                            <div class="row mb-3">
                            <div class="col-sm-12 text-center">
                                <a href="adicionar_user.php">Sing-in</a>
                                </div>
                            </div>
                            <div class="row mb-3">
                                
                                <div class="col-sm-12 text-center">
                                    
                                    <input type="submit" value="ENTRAR" class="btn btn-primary px-5">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

