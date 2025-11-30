var BASE_URL = '/homeCare/lab-desenvolvimento-software-Thomaz/public';
var BASE_APP = '/homeCare/lab-desenvolvimento-software-Thomaz/app/Views/';

function novoMedico() {
      $.ajax({
        url: BASE_URL + '/medicos/novoMedico',
        method: 'POST',
        data: {formData: $('#formMedico').serialize()},
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                limparFormularioMedico();
                consultaMedicos();
                alert('Medico cadastrado com sucesso!');
            } else {
                alert(response.message || 'Erro ao cadastrar medico');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao cadastrar medico:', error);
        }
    });
}
function limparFormularioMedico() {
    $('#formMedico')[0].reset();
}

function consultaMedicos() {
    $.ajax({
        url: BASE_URL + '/medicos/listar',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                renderizarTabelaMedicos(response.data);
            } else {
                alert('Erro ao consultar medicos');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao consultar medicos:', error);
        }
    });
}

function renderizarTabelaMedicos(medicos) {
    var tabelaBody = $('#tabelaMedicos tbody');
    tabelaBody.empty();
    medicos.forEach(function(medico) {
        var row = '<tr>' +
            '<td>' + medico.nome + '</td>' +
            '<td>' + medico.crm + '</td>' +
            '<td>' + medico.especialidade + '</td>' +
            '<td>' + medico.telefone + '</td>' +
            '<td><button type="button" onclick="alterarMedico(' + medico.id + ')" class="btn btn-sm btn-info">Alterar</button></td>' +
            '</tr>';
        tabelaBody.append(row);
    });
}

function alterarMedico(id) {
    // Busca os dados atuais do médico para preencher o formulário
    $.ajax({
        url: BASE_URL + '/medicos/getMedicoPorId',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var medico = response.data;
                var dialogContent = '<div id="alterarMedico" title="Alterar Médico">' +
                    '<form id="formAlterarMedico">' +
                    '<input type="hidden" name="id_medico" value="' + medico.id + '">' +
                    '<div class="form-group">' +
                    '<label for="nomeAlterar">Nome:</label>' +
                    '<input type="text" class="form-control" id="nomeAlterar" name="nome" value="' + medico.nome + '" required>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="crmAlterar">CRM:</label>' +
                    '<input type="text" class="form-control" id="crmAlterar" name="crm" value="' + medico.crm + '" required>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="especialidadeAlterar">Especialidade:</label>' +
                    '<input type="text" class="form-control" id="especialidadeAlterar" name="especialidade" value="' + medico.especialidade + '" required>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="telefoneAlterar">Telefone:</label>' +
                    '<input type="text" class="form-control" id="telefoneAlterar" name="telefone" value="' + medico.telefone + '" required>' +
                    '</div>' +
                    '</form>' +
                    '</div>';
                $('body').append(dialogContent);

                // Abre a janela de diálogo
                $('#alterarMedico').dialog({
                    modal: true,
                    buttons: {
                        "Salvar": function() {
                            var dialogInstance = $(this);
                            var formData = $('#formAlterarMedico').serialize();
                            $.ajax({
                                url: BASE_URL + '/medicos/alterarMedico',
                                method: 'POST',
                                data: { formData: formData },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        consultaMedicos();
                                        alert('Médico alterado com sucesso!');
                                        dialogInstance.dialog("close");
                                    } else {
                                        alert(response.message || 'Erro ao alterar médico');
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Erro ao alterar médico:' + error);
                                    alert('Ocorreu um erro ao tentar alterar o médico.');
                                }
                            });
                        }
                    },
                    close: function() {
                        $(this).dialog("destroy").remove();
                    }
                });
            } else {
                alert(response.message || 'Erro ao buscar dados do médico.');
            }
        },
        error: function() {
            alert('Não foi possível carregar os dados do médico para edição.');
        }
    });
}

$(document).ready(function() {
    consultaMedicos();
});