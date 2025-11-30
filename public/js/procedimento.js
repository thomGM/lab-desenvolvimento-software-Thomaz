var BASE_URL = '/homeCare/lab-desenvolvimento-software-Thomaz/public';
var BASE_APP = '/homeCare/lab-desenvolvimento-software-Thomaz/app/Views/';

function novoProcedimento() {
      $.ajax({
        url: BASE_URL + '/procedimento/novoProcedimento',
        method: 'POST',
        data: {formData: $('#formProcedimento').serialize()},
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                limparFormularioProcedimento();
                consultaProcedimentos();
                alert('Procedimento cadastrado com sucesso!');
            } else {
                alert(response.message || 'Erro ao cadastrar procedimento');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao cadastrar procedimento:', error);
        }
    });
}
function limparFormularioProcedimento() {
    $('#formProcedimento')[0].reset();
}
function consultaProcedimentos() {
    $.ajax({
        url: BASE_URL + '/procedimento/listar',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                renderizarTabelaProcedimentos(response.data);
            }
            else {
                alert('Erro ao consultar procedimentos');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao consultar procedimentos:', error);
        }
    });
}
function renderizarTabelaProcedimentos(procedimentos) {
    var tabelaBody = $('#tabelaProcedimentos tbody');
    tabelaBody.empty();
    (procedimentos || []).forEach(function(procedimento) {
        var row = '<tr>' +
            '<td>' + procedimento.nome + '</td>' +  
            '<td>' + (procedimento.status == 1 ? 'Ativo' : 'Inativo') + '<input type="hidden" name="status" value="' + procedimento.status + '"></td>' +
            '<td><button type="button" onclick="alterarProcedimento(' + procedimento.id + ')" class="btn btn-sm btn-info">Alterar</button></td>' +
            '<td><button type="button" onclick="inativarProcedimento(' + procedimento.id + ')" class="btn btn-sm btn-danger">Inativar</button></td>' +
            '</tr>';
        tabelaBody.append(row);
    });
}
function alterarProcedimento(id) {
    $.ajax({
        url: BASE_URL + '/procedimento/getProcedimentoPorId',
        method: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var procedimento = response.data;
                var htmlContent = '<div>' +
                    '<label for="nome">Nome:</label>' +
                    '<input type="text" id="nomeProcedimento" name="nomeProcedimento" value="' + procedimento.nome + '" class="form-control"><br>' +
                    '</div>';
                
                var $dialogElement = $(htmlContent);
                    
                $dialogElement.dialog({
                    title: 'Alterar Procedimento',
                    modal: true,
                    width: 400,
                    buttons: {
                        'Salvar': function() {
                            var dialogInstance = $(this);
                            var nomeAlterado = $('#nomeProcedimento').val();
                            $.ajax({
                                url: BASE_URL + '/procedimento/alterarProcedimento',
                                method: 'POST',
                                data: { id: id, nome: nomeAlterado },
                                dataType: 'json',
                                success: function(resp) {
                                    if (resp.success) {
                                        consultaProcedimentos();
                                        alert('Procedimento alterado com sucesso!');
                                        dialogInstance.dialog('close');
                                    } else {
                                        alert(resp.message || 'Erro ao alterar procedimento');
                                    }
                                }
                            });
                        }
                    },
                    close: function() {
                        $(this).dialog('destroy').remove();
                    }
                });
            }
        }
    });
}
function inativarProcedimento($id) {
    $.ajax({
        url: BASE_URL + '/procedimento/inativarProcedimento',
        method: 'POST',
        data: { id: $id },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                consultaProcedimentos();
                alert('Procedimento inativado com sucesso!');
            } else {
                alert(response.message || 'Erro ao inativar procedimento');
            }
        }
    });
}

$(document).ready(function(){
    consultaProcedimentos();

    $('#filtroStatus').on('change', function() {
        var statusFiltro = $(this).val();
        
        if (statusFiltro === 'todos') {
            $('#tabelaProcedimentos tbody tr').show();
            return;
        }

        // Itera sobre cada linha da tabela para filtrar
        $('#tabelaProcedimentos tbody tr').each(function() {
            var linha = $(this);
            var statusLinha = linha.find('input[name="status"]').val();
            //console.log('statusLinha' + statusLinha);
            //console.log( 'statusFiltro' + statusFiltro)

            if (statusLinha === statusFiltro) {
                linha.show();
            } else {
                linha.hide();
            }
        });
    });
});