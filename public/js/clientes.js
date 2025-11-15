var BASE_URL = '/homeCare/lab-desenvolvimento-software-Thomaz/public';
var BASE_APP = '/homeCare/lab-desenvolvimento-software-Thomaz/app/Views/';


function criarCliente() {
        $.ajax({
            url: BASE_URL + '/clientes/novoCliente',
            method: 'POST',
            data: $('#formCliente').serialize(),
            success: function(response) {
                if (response.success) {
                    alert('Cliente cadastrado com sucesso!');
                    window.location.href = BASE_APP + 'clientes.php';
                } else {
                    alert(response.message || 'Erro ao cadastrar cliente');
                }
            },
            error: function(xhr, status, error) {
                console.log('Erro ao cadastrar cliente: ' + error);
            }
        });
}

var medicosCache = []; // Variável para armazenar a lista de médicos

function consultarMedicos() {
    if (medicosCache.length === 0) { // Só busca se o cache estiver vazio
        $.ajax({
            url: BASE_URL + '/medicos/listar',
            method: 'GET',
            dataType: 'json', // Garante que o jQuery vai tratar a resposta como JSON
            success: function(response) {
                medicosCache = response; // Armazena no cache
            },
            error: function(xhr, status, error) {
                console.error('Erro ao consultar médicos: ' + error);
                console.error('Resposta do servidor:', xhr.responseText);
                console.log('Ocorreu um erro ao buscar a lista de médicos. Verifique o console para mais detalhes.');
            }
        });
    }
}

var procedimentosCache = []; // Variável para armazenar a lista de procedimentos

function consultaProcedimentos() {
    if (procedimentosCache.length === 0) { // Só busca se o cache estiver vazio
        $.ajax({
            url: BASE_URL + '/procedimento/listar',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                procedimentosCache = response; // Armazena no cache
            },
            error: function(xhr, status, error) {
                console.log('Erro ao consultar procedimentos: ' + error);
            }
        });
    }
}

function novoHistoricoMedico(index, item = {}) {
    console.log("Adicionando novo histórico médico com índice:", index);
    const novoHistorico = `
        <div class="item" data-index="${index}">           
            <div class="form-group flex-group">
                <label for="id_medico_${index}" class="required">Médico</label> <button type="button" class="remover" aria-label="Remover histórico">-</button>
                <select id="id_medico_${index}" name="historicoMedico[${index}][id_medico]" class="form-control" required>
                    <option value="" disabled selected>Selecione o médico</option>
                </select>
            </div>
            <div class="form-group">
                <label for="descricaoHistorico_${index}">Descrição</label>
                <textarea id="descricaoHistorico_${index}" name="historicoMedico[${index}][descricao]" class="form-control" rows="4"></textarea>
            </div>
        </div>
    `;

    $('#divHistoricoMedico').append(novoHistorico);

    // Popula o select de médicos que acabou de ser criado
    const $select = $(`#id_medico_${index}`);
    if (medicosCache.length > 0) {
        medicosCache.forEach(medico => {
            $select.append(`<option value="${medico.id}">${medico.nome}</option>`);
        });
        // Se estiver editando, seleciona o médico correto
        if (item.id_medico) {
            $select.val(item.id_medico);
        }
    }

    const novoIndice = index + 1;
    $('.adicionar[onclick*="historicoMedico"]').attr('onclick', `historicoMedico(${novoIndice})`);
}

function novoMedicamentos(index, item = {}) {
    console.log("Adicionando novo medicamento com índice:", index);
    const novoMedicamento = `
        <div class="item" data-index="${index}">           
            <div class="form-group flex-group">
                <label for="nomeMedicamento_${index}" class="required">Nome do Medicamento</label>
                <button type="button" class="remover" aria-label="Remover medicamento">-</button>
                <input type="text" class="form-control" id="nomeMedicamento_${index}" name="medicamentos[${index}][nome]" value="${item.nome || ''}" required>
            </div>
            <div class="form-group flex-group">
                <label for="dosagemMedicamento_${index}" class="required">Dosagem</label>
                <input type="text" class="form-control" id="dosagemMedicamento_${index}" name="medicamentos[${index}][dosagem]" value="${item.dosagem || ''}" required>
            </div>
            <div class="form-group flex-group">
                <label for="frequenciaMedicamento_${index}" class="required">Frequência</label>
                <input type="text" class="form-control" id="frequenciaMedicamento_${index}" name="medicamentos[${index}][frequencia]" value="${item.frequencia || ''}" required>
            </div>
            <div class="form-group">
                <label for="viaAdministracao_${index}">Via de Administração</label>
                <input type="text" class="form-control" id="viaAdministracao_${index}" name="medicamentos[${index}][via]" value="${item.via || ''}" required>
            </div>
            <div class="form-group">
                <label for="inicioTratamento_${index}">Início do Tratamento</label>
                <div class="input-group">
                    <input type="date" class="form-control" id="inicioTratamento_${index}" name="medicamentos[${index}][dataInicio]" value="${item.dataInicio || ''}">
                    <input type="time" class="form-control" id="horaInicio_${index}" name="medicamentos[${index}][horaInicio]" value="${item.horaInicio || ''}">
                </div>
            </div>
            <div class="form-group">
                <label for="fimTratamento_${index}">Fim do Tratamento</label>
                <div class="input-group">
                    <input type="date" class="form-control" id="fimTratamento_${index}" name="medicamentos[${index}][dataFim]" value="${item.dataFim || ''}">
                    <input type="time" class="form-control" id="horaFim_${index}" name="medicamentos[${index}][horaFim]" value="${item.horaFim || ''}">
                </div>
            </div>
        </div>
    `;
    
    $('#divMedicamentos').append(novoMedicamento);
    
    const novoIndice = index + 1;
    $('.adicionar[onclick*="novoMedicamentos"]').attr('onclick', `novoMedicamentos(${novoIndice})`);
}

function novoRestricoesAlimentares(index, item = {}) {
    console.log("Adicionando nova restrição alimentar com índice:", index);
    const novaRestricao = `
        <div class="item" data-index="${index}">           
            <div class="form-group flex-group">
                <label for="descricaoRestricao_${index}">Detalhes</label>
                <button type="button" class="remover" aria-label="Remover restrição">-</button>
                <textarea id="descricaoRestricao_${index}" name="restricoesAlimentares[${index}][descricao]" class="form-control" rows="4"></textarea>
            </div>
        </div>
    `;
    
    $('#divRestricoesAlimentares').append(novaRestricao);
    
    const novoIndice = index + 1;
    $('.adicionar[onclick*="novoRestricoesAlimentares"]').attr('onclick', `novoRestricoesAlimentares(${novoIndice})`);
}

function novoProcedimentosEspecificos(index, item = {}) {
    console.log("Adicionando novo procedimento com índice:", index);
    const novoProcedimento = `
        <div class="item" data-index="${index}">           
            <div class="form-group flex-group">
                <label for="id_procedimento_${index}" class="required">Tipo de Procedimento</label>
                <button type="button" class="remover" aria-label="Remover procedimento">-</button>
                <select id="id_procedimento_${index}" name="procedimentosEspecificos[${index}][id_procedimento]" class="form-control" required>
                    <option value="" disabled selected>Selecione um procedimento</option>
                </select>
            </div>
            <div class="form-group flex-group">
                <label for="horaProcedimento_${index}">Hora do Procedimento</label>
                <input type="time" class="form-control" id="horaProcedimento_${index}" name="procedimentosEspecificos[${index}][hora]" value="${item.hora || ''}">
            </div>
            <div class="form-group">
                <label for="descricaoProcedimento_${index}">Detalhes</label>
                <textarea id="descricaoProcedimento_${index}" name="procedimentosEspecificos[${index}][descricao]" class="form-control" rows="4">${item.descricao || ''}</textarea>
            </div>
        </div>
    `;

    $('#divProcedimentosEspecificos').append(novoProcedimento);

    if (procedimentosCache.length > 0) {
        const $select = $(`#id_procedimento_${index}`);

        console.log("Populando select de procedimentos específicos");
        procedimentosCache.forEach(procedimento => {
            $select.append(`<option value="${procedimento.id}">${procedimento.nome}</option>`);
            console.log("Adicionado procedimento ao select:", procedimento.nome);
        });
        if (item.id_procedimento) {
            $select.val(item.id_procedimento);
        }
    }
    
    const novoIndice = index + 1;
    $('.adicionar[onclick*="novoProcedimentosEspecificos"]').attr('onclick', `novoProcedimentosEspecificos(${novoIndice})`);
}

$(document).ready(function() {
    // Pré-carrega a lista de médicos ao carregar a página
    consultarMedicos();
    consultaProcedimentos();

    if ($('input[name="historicoMedico"]').is(':checked')) {
        $('#historicoMedicoInfo').show();
    } else {
        $('#historicoMedicoInfo').hide();
    }

    $('input[name="historicoMedico"]').change(function() {
        if ($(this).is(':checked')) {
            $('#historicoMedicoInfo').show();
        } else {
            $('#historicoMedicoInfo').hide();
        }
    });

    if ($('input[name="medicamentos"]').is(':checked')) {
        $('#medicamentosInfo').show();
    } else {
        $('#medicamentosInfo').hide();
    }
    $('input[name="medicamentos"]').change(function() {
        if ($(this).is(':checked')) {
            $('#medicamentosInfo').show();
        } else {
            $('#medicamentosInfo').hide();
        }
    });

    if ($('input[name="restricoesAlimentares"]').is(':checked')) {
        $('#restricoesAlimentaresInfo').show();
    } else {
        $('#restricoesAlimentaresInfo').hide();
    }
    $('input[name="restricoesAlimentares"]').change(function() {
        if ($(this).is(':checked')) {
            $('#restricoesAlimentaresInfo').show();
        } else {
            $('#restricoesAlimentaresInfo').hide();
        }
    });

    if ($('input[name="procedimentosEspecificos"]').is(':checked')) {
        $('#procedimentosEspecificosInfo').show();
    } else {
        $('#procedimentosEspecificosInfo').hide();
    }
    $('input[name="procedimentosEspecificos"]').change(function() {
        if ($(this).is(':checked')) {
            $('#procedimentosEspecificosInfo').show();
        } else {
            $('#procedimentosEspecificosInfo').hide();
        }
    });

    // delegação para remover itens criados dinamicamente
    $(document).on('click', '.remover', function(){
        var $item = $(this).closest('.item');
        $item.slideUp(150, function(){ $item.remove(); });
    });
});