<?php
require_once('header.php');
use sys4soft\Database;
require_once('../libraries/config.php');
require_once('../libraries/Database.php');

// Gerar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login_index.php");
    exit();
}
// Preparar os dados do formulário e os erros
$inputs = [
    'data_consulta' => ['value' => '', 'erro' => ''],
    'time' => ['value' => '', 'erro' => ''],
    'observacoes' => ['value' => '', 'erro' => '']
];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proteção contra CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token inválido.");
    }
     // Sanitização e validação
    if(empty($_POST['data_consulta'])){
        $inputs['data_consulta']['erro'] = 'Data da consulta é de prenchimento obrigatorio.';
    }else{
        $inputs['data_consulta']['value']= filter_input(INPUT_POST, 'data_consulta');
        if (strtotime( $inputs['data_consulta']['value']) < time()) {
                $inputs['data_consulta']['erro']= "A data e hora da consulta não podem ser no passado.";
            }
         $dia_semana = date('N', strtotime($inputs['data_consulta']['value'])); // N = 1 (segunda-feira) a 7 (domingo)
        if ($dia_semana >= 6) { // 6 = Sábado, 7 = Domingo
                $inputs['data_consulta']['erro'] = 'Consultas não são permitidas em finais de semana.';
            }

    }
    if(empty($_POST['time'])){
        $inputs['time']['erro'] = 'Data da consulta é de prenchimento obrigatorio.';
    }else{
        $inputs['time']['value']= filter_input(INPUT_POST, 'time');
            if($inputs['time']['value'] < "08:00" || $inputs['time']['value'] > "19:00") {
                  $inputs['time']['erro'] = 'Não fazemos consultas fora do horário das 08:00 às 19:00.';
            }
    }
    if(empty($_POST['observacoes'])){
        $inputs['observacoes']['erro'] = 'A consulta é de prenchimento obrigatorio.';
    }else{
        $inputs['observacoes']['value']= filter_input(INPUT_POST, 'observacoes');
        if (strlen($inputs['observacoes']['value']) < 12) {
                $inputs['observacoes']['erro']= "A conteúdo tem que ter no minino 12 caracteres.";
            }
    }
    if (empty($inputs['data_consulta']['erro']) && empty($inputs['time']['erro'])) {
        // Combinar data e hora
        $data_hora_consulta = $inputs['data_consulta']['value'] . ' ' . $inputs['time']['value'];
    
        // Verificar se a data e hora da consulta estão no passado
        if (strtotime($data_hora_consulta) < time()) {
            $inputs['data_consulta']['erro'] = "A data e hora da consulta não podem ser no passado.";
        }
    }

    if (!empty($inputs['data_consulta']['erro']) || !empty($inputs['time']['erro']) || !empty($inputs['observacoes']['erro'])){
        $_SESSION['inputs'] = $inputs;
        header('Location: marcar_consulta_frm.php');
        exit;
    }

    $id_utilizador = $_SESSION['user_id'];

    // Combinar data e hora
    $data_hora_consulta = $inputs['data_consulta']['value'] . ' ' . $inputs['time']['value'];

    $database = new Database(MYSQL_CONFIG);
    $parames = [
        ':user_id' => $id_utilizador,
        ':observacoes' => $inputs['observacoes']['value'],
        ':data_consulta' => $data_hora_consulta,
    ];

    $result = $database->execute_non_query("INSERT INTO consultas (id_cliente, data_consulta, observacoes, created_at, updated_at) VALUES (:user_id, :data_consulta, :observacoes, NOW(), NOW())", $parames);

    header('Location: ../dashboard.php');
    exit();
}
?>