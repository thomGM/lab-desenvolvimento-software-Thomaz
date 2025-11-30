var BASE_URL = '/homeCare/lab-desenvolvimento-software-Thomaz/public';

$(document).ready(function() {
    carregarProcedimentosDia();
    
    $(document).on('change', '.task-toggle', function() {
        const $taskItem = $(this).closest('.task-item');
        
        if ($(this).is(':checked')) {
            $taskItem.addClass('completed');
            console.log('Tarefa concluída:', $taskItem.find('h3').text());
        } else {
            $taskItem.removeClass('completed');
            console.log('Tarefa desmarcada:', $taskItem.find('h3').text());
        }
    });
});

function carregarProcedimentosDia() {
    const hoje = new Date();
    const ano = hoje.getFullYear();
    const mes = hoje.getMonth() + 1;
    const dia = hoje.getDate();
    
    $.ajax({
        url: BASE_URL + '/agenda/getEventosDia',
        method: 'GET',
        data: {
            cliente_id: CLIENTE_ID,
            data: hoje.toISOString().split('T')[0]
        },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.data.length > 0) {
                renderizarTarefas(response.data);
            } else {
                $('.tasks-container').html('<p>Nenhum procedimento agendado para hoje.</p>');
            }
        },
        error: function() {
            $('.tasks-container').html('<p>Erro ao carregar procedimentos.</p>');
        }
    });
}

function renderizarTarefas(eventos) {
    const container = $('.tasks-container');
    container.empty();
    const inicio = `<div class="task-item" data-evento-id="inicio">
                <div class="task-info">
                    <h3>Inicio do Turno</h3>
                </div>
                <label class="switch">
                    <input type="checkbox" class="task-toggle">
                    <span class="slider"></span>
                </label>
            </div>`;
    container.append(inicio);
    
    eventos.forEach(function(evento) {
        const taskHtml = `
            <div class="task-item" data-evento-id="${evento.id}">
                <div class="task-info">
                    <h3>${evento.nome}</h3>
                    <p>${evento.descricao || 'Procedimento agendado'}</p>
                    <span class="task-time">Agendado para: ${evento.horario || 'Horário não definido'}</span>
                </div>
                <label class="switch">
                    <input type="checkbox" class="task-toggle">
                    <span class="slider"></span>
                </label>
            </div>
        `;
        container.append(taskHtml);
    });

    const fim = `<div class="task-item" data-evento-id="fim">
                <div class="task-info">
                    <h3>Fim do Turno</h3>
                </div>
                <label class="switch">
                    <input type="checkbox" class="task-toggle">
                    <span class="slider"></span>
                </label>
            </div>`;
    container.append(fim);
}

