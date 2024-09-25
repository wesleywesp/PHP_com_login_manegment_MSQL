$(document).ready(function () {
    // Valida o formulário
    function validFormulario(idfor) {
        const frm = $('#' + idfor);

        frm.on('submit', function (e) {
            e.preventDefault(); // Impede o comportamento padrão de submissão

            const nome = $('#nome').val();
            const apelido = $('#apelido').val();
            const telefone = $('#telefone').val();
            const email = $('#email').val();
            const motivo = $.trim($("#motivo").val());
            const tipoPagina = $('#tipoPagina').val();
            const prazoMeses = $('#prazoMeses').val();
            const separadores = $('input[type=checkbox]:checked').length;

            // Verifica se os campos obrigatórios estão preenchidos
            if (nome === '' || apelido === '' || telefone === '' || email === "" || motivo === "") {
                $('#msg').html('Preencha todos os campos obrigatórios').css('color', 'red');
                setTimeout(() => {
                    $('#msg').html('');
                }, 3000);
                return false;
            }

            // Verifica se o prazo e separadores foram selecionados corretamente
            if (isNaN(prazoMeses) || separadores === 0) {
                $('#msg').html('Preencha corretamente os campos de prazo e selecione ao menos um separador').css('color', 'red');
                setTimeout(() => {
                    $('#msg').html('');
                }, 3000);
                return false;
            }

            // Envia o formulário via AJAX para o PHP calcular o orçamento
            $.ajax({
                type: 'POST',
                url: 'php/dados.php',  // PHP que fará o cálculo
                data: frm.serialize(),
                success: function (response) {
                    $('#orcamentoFinal').html(response).css('color', 'green');
                },
                error: function () {
                    $('#orcamentoFinal').html('Erro ao processar o orçamento').css('color', 'red');
                }
            });
        });
    }

    // Validações em tempo real (opcional)
    $('#nome, #apelido, #telefone, #email').keyup(function () {
        let id = $(this).attr('id');
        if ($(this).val().length < 3) {
            $(this).css('border', '2px solid red');
        } else {
            $(this).css('border', '2px solid green');
        }
    });

    $('#telefone').keyup(function () {
        if (this.value.length !== 9 || isNaN(this.value)) {
            $(this).css('border', '2px solid red');
        } else {
            $(this).css('border', '2px solid green');
        }
    });

    $('#email').keyup(function () {
        const emailPattern = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
        if (!emailPattern.test(this.value)) {
            $(this).css('border', '2px solid red');
        } else {
            $(this).css('border', '2px solid green');
        }
    });

    validFormulario('budgetForm'); // Inicializa a validação no formulário de orçamento
});
