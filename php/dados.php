<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber os dados do formulário
    $tipoPagina = $_POST['tipoPagina'];
    $prazoMeses = (int) $_POST['prazoMeses'];
    $separadores = isset($_POST['separador']) ? $_POST['separador'] : [];

    // Definir o preço base dependendo do tipo de página
    $precoBase = 0;
    switch ($tipoPagina) {
        case 'landing_page':
            $precoBase = 1000;
            break;
        case 'site_basico':
            $precoBase = 2000;
            break;
        case 'site_avancado':
            $precoBase = 3000;
            break;
        case 'ecommerce':
            $precoBase = 5000;
            break;
        default:
            echo "Tipo de página inválido";
            exit();
    }

    // Calcular o desconto baseado no prazo
    $desconto = min($prazoMeses * 0.05, 0.20); // 5% por mês, máximo de 20%

    // Calcular o valor dos separadores
    $totalSeparadores = 0;
    foreach ($separadores as $separador) {
        $totalSeparadores += (float) $separador;
    }

    // Calcular o orçamento final
    $total = $precoBase + $totalSeparadores;
    $total -= $total * $desconto; // Aplicar desconto

    // Exibir o orçamento formatado
    echo number_format($total, 2, ',', '.') . "€";
}
?>
