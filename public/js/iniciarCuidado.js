var BASE_URL = '/homeCare/lab-desenvolvimento-software-Thomaz/public';
var FAMILIAR_WHATSAPP = '';

$(document).ready(function() {
    carregarProcedimentosDia();
    
    $(document).on('change', '.task-toggle', function() {
        const $taskItem = $(this).closest('.task-item');
        
        if ($(this).is(':checked')) {
            $.ajax({
                url: BASE_URL + '/agenda/marcarConcluida',
                method: 'POST',
                data: {
                    paciente: CLIENTE_ID,
                    evento_id: $taskItem.data('evento-id'),
                    evento_tipo: $taskItem.data('evento-tipo'),
                    hora: $taskItem.data('data-evento')
                },
                success: function(response) {
                    $taskItem.addClass('completed');
                    console.log('Tarefa concluída:', $taskItem.find('h3').text());
                    if ($taskItem.data('evento-tipo') == 'f') {
                        console.log('fim do turno');
                        enviarRelatorio();
                    }
                },
                error: function() {
                    console.error('Erro ao marcar tarefa');
                }
            });
        } else {
            console.log('desmarcar');
            console.log($taskItem.data('evento-id'))
            console.log($taskItem.data('evento-tipo'))
            console.log($taskItem.data('data-evento'))
            $.ajax({
                url: BASE_URL + '/agenda/desmarcarConcluida',
                method: 'POST',
                data: {
                    paciente: CLIENTE_ID,
                    evento_id: $taskItem.data('evento-id'),
                    evento_tipo: $taskItem.data('evento-tipo'),
                    hora: $taskItem.data('data-evento')
                },
                success: function(response) {
                    $taskItem.removeClass('completed');
                    console.log('Tarefa desmarcada:', $taskItem.find('h3').text());
                },
                error: function() {
                    console.error('Erro ao desmarcar tarefa');
                }
            });
        }
    });
});

function enviarRelatorio() {
    $.ajax({
        url: BASE_URL + '/gemini/enviarRelatorio',
        method: 'POST',
        data: {
            paciente: CLIENTE_ID,
            data: $('#dataDia').val()
        },
        success: function(response) {
            console.log('Relatório enviado com sucesso.');
            console.log(response.relatorio)
            // abrir um dialog com o retorno de gemini
            var dialogHtml = '<div id="dialog" style="text-align: center; padding: 20px;">' +
                                '<p><b>Relatório do Dia:</b></p>' +
                                '<p>' + response.relatorio + '</p>' +
                                '</div>';
            var $dialog = $(dialogHtml);
            $dialog.dialog({
                title: 'Relatório Enviado',
                modal: true,
                width: 400,
                buttons: {
                    Enviar: function() {
                        console.log(' WhatsApp do familiar...' + FAMILIAR_WHATSAPP);
                        // enviar mensagem para o whatsapp do familar
                        window.open('https://wa.me/' + FAMILIAR_WHATSAPP + '?text=' + encodeURIComponent($('#dialog p:nth-child(2)').text()), '_blank');
                        $(this).dialog('close');
                    },
                    Cancelar: function() {
                        $(this).dialog('close');
                    }
                }
            });
        },
        error: function() {
            console.error('Erro ao enviar relatório.');
        }
    });
}

function carregarProcedimentosDia() {   
    $.ajax({
        url: BASE_URL + '/agenda/getEventosDia',
        method: 'GET',
        data: {
            cliente_id: CLIENTE_ID,
            data: $('#dataDia').val()
        },
        dataType: 'json',
        success: function(response) {
            FAMILIAR_WHATSAPP = response.telefone;

            if (response.success && response.data.length > 0) {
                renderizarTarefas(response.data, response.inicio, response.fim);
            } else {
                $('.tasks-container').html('<p>Nenhum procedimento agendado para hoje.</p>');
            }
        },
        error: function() {
            $('.tasks-container').html('<p>Erro ao carregar procedimentos.</p>');
        }
    });
}

function renderizarTarefas(eventos, checkincio, checkfim) {
    console.log('inicio ' + checkincio);
    console.log('fim ' + checkfim);

    const container = $('.tasks-container');
    container.empty();

    const checkedInicio = checkincio ? 'checked' : '';
    const completeInicio = checkincio ? 'completed' : '';
    const inicio = `<div class="task-item ${completeInicio}" data-evento-id="inicio" data-evento-tipo="i">
                <div class="task-info">
                    <h3>Inicio do Turno</h3>
                </div>
                <label class="switch">
                    <input type="checkbox" class="task-toggle" ${checkedInicio}>
                    <span class="slider"></span>
                </label>
            </div>`;
    container.append(inicio);
    
    eventos.forEach(function(evento) {
        const checked = evento.concluida ? 'checked' : '';
        const completedClass = evento.concluida ? 'completed' : '';
        
        const taskHtml = `
            <div class="task-item ${completedClass}" data-evento-id="${evento.id}" data-evento-tipo="${evento.tipo_evento}" data-data-evento="${evento.horario}">
                <div class="task-info">
                    <h3>${evento.nome}</h3>
                    <p>${evento.descricao || 'Procedimento agendado'}</p>
                    <span class="task-time">Agendado para: ${evento.horario || 'Horário não definido'}</span>
                </div>
                <label class="switch">
                    <input type="checkbox" class="task-toggle" ${checked}>
                    <span class="slider"></span>
                </label>
            </div>
        `;
        container.append(taskHtml);
    });

    const checkedFim = checkfim ? 'checked' : '';
    const completeFim = checkfim ? 'completed' : '';
    const fim = `<div class="task-item ${completeFim}" data-evento-id="fim" data-evento-tipo="f">
                <div class="task-info">
                    <h3>Fim do Turno</h3>
                </div>
                <label class="switch">
                    <input type="checkbox" class="task-toggle" ${checkedFim}>
                    <span class="slider"></span>
                </label>
            </div>`;
    container.append(fim);
}

