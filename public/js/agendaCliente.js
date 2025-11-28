var BASE_URL = '/homeCare/lab-desenvolvimento-software-Thomaz/public';

$(document).ready(function() {
    let currentDate = new Date();

    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth(); // 0-11

        $('#month-year').text(date.toLocaleString('pt-BR', { month: 'long', year: 'numeric' }));

        const firstDayOfMonth = new Date(year, month, 1);
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const startDayOfWeek = firstDayOfMonth.getDay(); // 0=Dom, 1=Seg, ...

        const calendarGrid = $('#calendar-grid');
        calendarGrid.empty();

        // Preencher dias vazios no início
        for (let i = 0; i < startDayOfWeek; i++) {
            calendarGrid.append('<div class="day-cell empty"></div>');
        }

        // Preencher os dias do mês
        for (let day = 1; day <= daysInMonth; day++) {
            calendarGrid.append(`
                <div class="day-cell" id="day-${day}">
                    <div class="day-number">${day}</div>
                </div>
            `);
        }

        // Buscar e adicionar eventos
        fetchEvents(year, month + 1);
    }

    function fetchEvents(year, month) {
        $.ajax({
            url: BASE_URL + '/agenda/getEventos',
            method: 'GET',
            data: {
                cliente_id: CLIENTE_ID,
                ano: year,
                mes: month
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // A resposta agora é um array de eventos, podemos iterar diretamente
                    response.data.forEach(function(event) {
                        const eventDate = new Date(event.data_evento);
                        const day = eventDate.getDate(); // Não precisa mais do ajuste +1
                        const eventTypeClass = 'tipo-' + (event.tipo_evento || 'default').toLowerCase();

                        $(`#day-${day}`).append(`
                            <div class="event-postit ${eventTypeClass}" title="${event.nome}">
                                ${event.nome}
                            </div>
                        `);
                    });
                }
            },
            error: function(xhr) {
                console.error("Erro na requisição:", xhr.responseText);
                alert('Erro ao carregar os eventos da agenda.');
            }
        });
    }

    $('#next-month').on('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    $('#prev-month').on('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    // Renderização inicial
    renderCalendar(currentDate);
    
});