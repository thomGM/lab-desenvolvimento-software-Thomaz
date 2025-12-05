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
                <textarea id="descricaoHistorico_${index}" name="historicoMedico[${index}][descricao]" class="form-control" rows="4">${item.descricao || ''}</textarea>
            </div>
        </div>
    `;

    $('#divHistoricoMedico').append(novoHistorico);

    // Popula o select de médicos que acabou de ser criado
    const $select = $(`#id_medico_${index}`);
    
    if (medicosCache.length > 0) {
        console.log("Populando select de médicos com cache");
        medicosCache.forEach(medico => {
            $select.append(`<option value="${medico.id}">${medico.nome}</option>`);
        });
        if (item.id_medico) {
            $select.val(item.id_medico);
        }
    } else {
        console.log("Cache vazio, carregando médicos...");
        // Se o cache estiver vazio, carrega os médicos
        $.ajax({
            url: BASE_URL + '/medicos/listar',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                medicosCache = response.data;
                medicosCache.forEach(medico => {
                    $select.append(`<option value="${medico.id}">${medico.nome}</option>`);
                });
                if (item.id_medico) {
                    $select.val(item.id_medico);
                }
            },
            error: function(xhr, status, error) {
                console.log('Erro ao carregar médicos: ' + error);
            }
        });
    }

    const novoIndice = index + 1;
    $('.adicionar[onclick*="historicoMedico"]').attr('onclick', `historicoMedico(${novoIndice})`);
}

function novoMedicamentos(index, item = {}) {
    console.log("Adicionando novo medicamento com índice:", index);
    var ultima_aplicacao_data = '';
    var ultima_aplicacao_hora = '';
    var inicioTratamento_data = '';
    var fimTratamento_data = '';

    // Verifica se item.ultima_aplicacao existe antes de usar .split()
    if (item.ultima_aplicacao) {
        var partes = item.ultima_aplicacao.split(' ');
        ultima_aplicacao_data = partes[0].split(' ')[0];
        ultima_aplicacao_hora = partes[1];
    }
    // Verifica se item.inicioTratamento existe antes de usar .split()
    if (item.inicioTratamento) {
        inicioTratamento_data = item.inicioTratamento.split(' ')[0];
    }
    // Verifica se item.fimTratamento existe antes de usar .split()
    if (item.fimTratamento) {
        fimTratamento_data = item.fimTratamento.split(' ')[0];
    }
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
            <div class="form-group">
                <label for="viaAdministracao_${index}">Via de Administração</label>
                <input type="text" class="form-control" id="viaAdministracao_${index}" name="medicamentos[${index}][viaAdministracao]" value="${item.viaAdministracao || ''}" required>
            </div>
            <div class="form-group">
                <label for="inicioTratamento_${index}">Início do Tratamento</label>
                <input type="date" class="form-control" id="inicioTratamento_${index}" name="medicamentos[${index}][dataInicio]" value="${inicioTratamento_data}">
            </div>
            <div class="form-group">
                <label for="fimTratamento_${index}">Fim do Tratamento</label>
                <input type="date" class="form-control" id="fimTratamento_${index}" name="medicamentos[${index}][dataFim]" value="${fimTratamento_data}">
            </div>
            <div class="form-group">
                <label for="ultima_aplicacao_${index}">Ultima Aplicação</label>
                <div class="form-group">
                    <input type="date" class="form-control" id="ultima_aplicacao_data${index}" name="medicamentos[${index}][ultima_aplicacao_data]" value="${ultima_aplicacao_data}">
                    <input type="time" class="form-control" id="ultima_aplicacao_hora${index}" name="medicamentos[${index}][ultima_aplicacao_hora]" value="${ultima_aplicacao_hora}">
                </div>
            </div>
            <div class="form-group">
                <label for="repetir_${index}">Repetir:</label>
                <select class="form-control" id="repetir_${index}" name="medicamentos[${index}][repetir]">
                    <option value="1" ${item.repetir == 1 ? 'selected' : ''}>Todos os dias</option>
                    <option value="2" ${item.repetir == 2 ? 'selected' : ''}>Dias especificos</option>
                    <option value="3" ${item.repetir == 3 ? 'selected' : ''}>Intervalo de dias</option>
                </select>
            </div>
            <div id="diasMedicamento_${index}" class="form-group"></div>
            <div class="form-group">
                <label for="intervalo_${index}">Intervalo (Horário)</label>
                <select class="form-control" id="intervalo_${index}" name="medicamentos[${index}][intervalo]">
                    <option value="">Selecione</option>
                    <option value="1" ${item.intervalo == 1 ? 'selected' : ''}>Intervalo em horas</option>
                    <option value="2" ${item.intervalo == 2 ? 'selected' : ''}>Intervalo em minutos</option>
                    <option value="3" ${item.intervalo == 3 ? 'selected' : ''}>Intervalo especifico</option>
                </select>
            </div>
            <div id="horasMedicamento_${index}" class="form-group"></div>
        </div>
    `;
    
    $('#divMedicamentos').append(novoMedicamento);
    
    // Se estiver editando (item existe), chama as funções para criar e preencher os campos dinâmicos
    if (item.repetir) {
        atualizarCamposRepetir(index, item.repetir, item);
    }
    if (item.intervalo) {
        atualizarCamposIntervalo(index, item.intervalo, item);
    }

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
                <textarea id="descricaoRestricao_${index}" name="restricoesAlimentares[${index}][descricao]" class="form-control" rows="4">${item.descricao || ''}</textarea>
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
                <input type="time" class="form-control" id="horaProcedimento_${index}" name="procedimentosEspecificos[${index}][hora]" value="${item.horarios || ''}">
            </div>
            <div class="form-group">
                <label for="descricaoProcedimento_${index}">Detalhes</label>
                <textarea id="descricaoProcedimento_${index}" name="procedimentosEspecificos[${index}][descricao]" class="form-control" rows="4">${item.descricao || ''}</textarea>
            </div>
        </div>
    `;

    $('#divProcedimentosEspecificos').append(novoProcedimento);

    // Popula o select com procedimentos
    const $select = $(`#id_procedimento_${index}`);
    
    if (procedimentosCache.length > 0) {
        console.log("Populando select de procedimentos específicos com cache");
        procedimentosCache.forEach(procedimento => {
            $select.append(`<option value="${procedimento.id}">${procedimento.nome}</option>`);
        });
        if (item.id_procedimento) {
            $select.val(item.id_procedimento);
        }
    } else {
        console.log("Cache vazio, carregando procedimentos...");
        // Se o cache estiver vazio, carrega os procedimentos
        $.ajax({
            url: BASE_URL + '/procedimento/listar',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                procedimentosCache = response.data;
                procedimentosCache.forEach(procedimento => {
                    $select.append(`<option value="${procedimento.id}">${procedimento.nome}</option>`);
                });
                if (item.id_procedimento) {
                    $select.val(item.id_procedimento);
                }
            },
            error: function(xhr, status, error) {
                console.log('Erro ao carregar procedimentos: ' + error);
            }
        });
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

    $(document).on('change', 'select[name*="[repetir]"]', function() {
        var index = $(this).closest('.item').data('index');
        var valor = $(this).val();
        atualizarCamposRepetir(index, valor);
    });

    $(document).on('change', 'select[name*="[intervalo]"]', function() {
        var index = $(this).closest('.item').data('index');
        var valor = $(this).val();
        atualizarCamposIntervalo(index, valor);
    });    
});

function atualizarCamposRepetir(index, valor, item = {}) {
    var $targetDiv = $(`#diasMedicamento_${index}`);
    var html = '';
    $targetDiv.empty();

    switch (String(valor)) {
        case '2':
            html = `<label for="diasSemana_${index}">Selecione os dias da semana:</label><br>
                    <select id="diasSemana_${index}" name="medicamentos[${index}][diasSemana][]" class="form-control" multiple>
                        <option value="1">Domingo</option>
                        <option value="2">Segunda</option>
                        <option value="3">Terça</option>
                        <option value="4">Quarta</option>
                        <option value="5">Quinta</option>
                        <option value="6">Sexta</option>
                        <option value="7">Sábado</option>
                    </select>`;
            $targetDiv.html(html);
            if (item.diasMedicamento) {
                const dias = typeof item.diasMedicamento === 'string' ? item.diasMedicamento.split(',') : item.diasMedicamento;
                $(`#diasSemana_${index}`).val(dias);
            }
            break;
        case '3':
            html = `<label for="intervaloDias_${index}">Intervalo de dias:</label>
                    <input type="number" id="intervaloDias_${index}" name="medicamentos[${index}][intervaloDias]" class="form-control" min="1" placeholder="Número de dias">`;
            $targetDiv.html(html);
            if (item.diasMedicamento) {
                $(`#intervaloDias_${index}`).val(item.diasMedicamento);
            }
            break;
    }
}

function atualizarCamposIntervalo(index, valor, item = {}) {
    var $targetDiv = $(`#horasMedicamento_${index}`);
    var html = '';
    $targetDiv.empty();

    switch (String(valor)) {
        case '1':
            html = `<label for="horasIntervalo_${index}">Intervalo em horas:</label>
                    <input type="number" id="horasIntervalo_${index}" name="medicamentos[${index}][horasIntervalo]" class="form-control" min="1" placeholder="Número de horas">`;
            $targetDiv.html(html);
            if (item.horasMedicamento) $(`#horasIntervalo_${index}`).val(item.horasMedicamento);
            break;
        case '2':
            html = `<label for="minutosIntervalo_${index}">Intervalo em minutos:</label>
                    <input type="number" id="minutosIntervalo_${index}" name="medicamentos[${index}][minutosIntervalo]" class="form-control" min="1" placeholder="Número de minutos">`;
            $targetDiv.html(html);
            if (item.horasMedicamento) $(`#minutosIntervalo_${index}`).val(item.horasMedicamento);
            break;
        case '3':
            html = `<label for="horariosEspecificos_${index}">Horários específicos (separados por vírgula):</label>
                    <input type="text" id="horariosEspecificos_${index}" name="medicamentos[${index}][horariosEspecificos]" class="form-control" placeholder="Ex: 08:00, 12:00, 18:00">`;
            $targetDiv.html(html);
            if (item.horasMedicamento) $(`#horariosEspecificos_${index}`).val(item.horasMedicamento);
            break;
    }
}