<?php
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