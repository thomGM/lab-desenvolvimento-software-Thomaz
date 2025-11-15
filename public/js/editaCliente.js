var BASE_URL = '/homeCare/lab-desenvolvimento-software-Thomaz/public';
var BASE_APP = '/homeCare/lab-desenvolvimento-software-Thomaz/app/Views/';
console.log("CLIENTE_ID: " + CLIENTE_ID);
$(document).ready(function() {
    $.ajax({
        url: BASE_URL + '/clientes/buscarPorId',
        method: 'GET',
        data: {id: CLIENTE_ID},
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data) {
                const data = response.data;

                // 1. Preencher dados pessoais do cliente
                if (data.cliente && data.cliente.length > 0) {
                    const cliente = data.cliente[0];
                    $('#nome').val(cliente.nome);
                    $('#cpf').val(cliente.cpf);
                    // O formato da data pode precisar de ajuste (YYYY-MM-DD)
                    $('#dataNascimento').val(cliente.dataNascimento.split(' ')[0]);
                    $('#endereco').val(cliente.endereco);
                    $('#telefone').val(cliente.telefone);
                    $('#telefoneEmergencia').val(cliente.telefoneEmergencia);
                }

                // 2. Preencher Ficha Técnica
                if (data.fichaTecnica && data.fichaTecnica.length > 0) {
                    const ficha = data.fichaTecnica[0];

                    // Histórico Médico
                    if (parseInt(ficha.historicoMedico) === 1 && data.historicoMedico.length > 0) {
                        $('#historicoMedico').prop('checked', true).change();
                        data.historicoMedico.forEach((item, index) => {
                            novoHistoricoMedico(index, item);
                        });
                    }

                    // Medicamentos
                    if (parseInt(ficha.medicamentos) === 1 && data.medicamentos.length > 0) {
                        $('#medicamentos').prop('checked', true).change();
                        data.medicamentos.forEach((item, index) => {
                            novoMedicamentos(index, item);
                        });
                    }

                    // Restrições Alimentares
                    if (parseInt(ficha.restricoesAlimentares) === 1 && data.restricoesAlimentares.length > 0) {
                        $('#restricoesAlimentares').prop('checked', true).change();
                        data.restricoesAlimentares.forEach((item, index) => {
                            novoRestricoesAlimentares(index, item);
                        });
                    }

                    // Procedimentos Específicos
                    if (parseInt(ficha.procedimentosEspecificos) === 1 && data.procedimentosEspecificos.length > 0) {
                        $('#procedimentosEspecificos').prop('checked', true).change();
                        data.procedimentosEspecificos.forEach((item, index) => {
                            novoProcedimentosEspecificos(index, item);
                        });
                    }
                }

            } else {
                alert(response.message || 'Erro ao carregar dados do cliente.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao buscar cliente:', error);
        }
    });
});

function atualizarCliente() {
    $.ajax({
        url: BASE_URL + '/clientes/atualizar',
        method: 'POST',
        data: {formData: $('#formCliente').serialize()},
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert('Cliente atualizado com sucesso!');
                window.location.href = BASE_APP + 'clientes.php';
            } else {
                alert(response.message || 'Erro ao atualizar cliente');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro ao atualizar cliente:', error);
        }
    });
}