var BASE_URL = '/homeCare/lab-desenvolvimento-software-Thomaz/public';
var BASE_APP = '/homeCare/lab-desenvolvimento-software-Thomaz/app/Views/';
    
$(document).ready(function() {
    $.ajax({
        url: BASE_URL + '/clientes/listar',
        method: 'GET',
        success: function(response) {
            console.log('Resposta recebida:', response);
            const $tabelaCorpo = $('#tabela-clientes tbody');
            $tabelaCorpo.empty(); 
            response.forEach(function(cliente) {
                const linha = `
                    <tr>
                        <td><a href="${BASE_APP}editarCliente.php?id=${cliente.id}" class="editar">${cliente.id}</a></td>
                        <td>${cliente.nome}</td>
                        <td>${cliente.cpf ? cliente.cpf : ''}</td>
                        <td>${cliente.telefoneEmergencia}</td>
                    </tr>
                `;
                $tabelaCorpo.append(linha);
            });
        },
        error: function(xhr, status, error) {
            console.error('Erro na requisição:', error);
        }
    });
});

function pesquisar() {
    const nomePesquisa = $('#cliente-nome').val();
    const tipoPesquisa = $('#tipo-pesquisa').val();
    $.ajax({
        url: BASE_URL + '/clientes/buscar',
        method: 'GET',
        data: { nome: nomePesquisa, tipo: tipoPesquisa },
        dataType: 'json',
        success: function(response) {
            if (response.success === false) {
                alert(response.message || 'Erro ao buscar clientes');
                return;
            } else {
                const $tabelaCorpo = $('#tabela-clientes tbody');
                $tabelaCorpo.empty();
                (response.data || []).forEach(function(cliente) {
                    const linha = `
                        <tr>
                            <td><a href="${BASE_APP}editarCliente.php?id=${cliente.id}" class="editar">${cliente.id}</a></td>
                            <td>${cliente.nome}</td>
                            <td>${cliente.cpf ? cliente.cpf : ''}</td>
                            <td>${cliente.telefoneEmergencia}</td>
                        </tr>
                    `;
                    $tabelaCorpo.append(linha);
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro na requisição:', error);
        }
    });
}

function incluirNovo() {
    window.location.href = BASE_APP + 'novoCliente.php';
}
