var BASE_URL = '/homeCare/lab-desenvolvimento-software-Thomaz/public';
var BASE_APP = '/homeCare/lab-desenvolvimento-software-Thomaz/app/Views/';

$(document).ready(function() {
    carregarClientes();

    $('#search-cliente').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();

        $('.cliente-card').each(function() {
            const nomeCliente = $(this).find('.cliente-nome').text().toLowerCase();
            if (nomeCliente.includes(searchTerm)) {
                $(this).show(); 
            } else {
                $(this).hide(); 
            }
        });
    });
});

function carregarClientes() {
    $.ajax({
        url: BASE_URL + '/clientes/listar',
        method: 'GET',
        dataType: 'json',
        success: function(clientes) {
            const container = $('#clientes-container');
            container.empty(); 

            if (clientes && clientes.length > 0) {
                clientes.forEach(function(cliente) {
                    const cardHtml = `
                        <div class="cliente-card" data-id="${cliente.id}">
                            <div class="cliente-nome">${cliente.nome}</div>
                        </div>
                    `;
                    container.append(cardHtml);
                });
            } else {
                container.html('<p>Nenhum cliente encontrado.</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error("Erro ao carregar clientes:", error);
            $('#clientes-container').html('<p>Ocorreu um erro ao carregar a lista de clientes.</p>');
        }
    });

    $(document).on('click', '.cliente-card', function() {
        const clienteId = $(this).data('id');
        const clienteNome = $(this).find('.cliente-nome').text();

        window.location.href = BASE_APP + 'agendaCliente.php?id=' + clienteId;
    });
}