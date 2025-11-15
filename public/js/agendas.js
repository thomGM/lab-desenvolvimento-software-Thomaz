var BASE_URL = '/homeCare/lab-desenvolvimento-software-Thomaz/public';

$(document).ready(function() {
    carregarClientes();
});

function carregarClientes() {
    $.ajax({
        url: BASE_URL + '/clientes/listar',
        method: 'GET',
        dataType: 'json',
        success: function(clientes) {
            const container = $('#clientes-container');
            container.empty(); // Limpa o container antes de adicionar os novos cartões

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

    // Adiciona o evento de clique nos cartões
    $(document).on('click', '.cliente-card', function() {
        const clienteId = $(this).data('id');
        const clienteNome = $(this).find('.cliente-nome').text();
        // Por enquanto, vamos apenas mostrar um alerta.
        // No futuro, aqui você pode redirecionar para a página da agenda do cliente.
        alert(`Você clicou no cliente: ${clienteNome} (ID: ${clienteId}).\nAqui abriria a agenda dele.`);
        // Exemplo de redirecionamento:
        // window.location.href = 'agendaCliente.php?id=' + clienteId;
    });
}