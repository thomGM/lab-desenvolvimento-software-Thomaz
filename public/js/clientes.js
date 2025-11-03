function pesquisar() {
    ajax({
        url: '/clientes/pesquisar',
        method: 'POST',
        data: {
            nome: $('#cliente-nome').val()
        },
        success: function(response) {
            // Manipule a resposta do servidor
        },
        error: function(xhr, status, error) {
            console.error('Erro na requisição:', error);
        }
    });
}