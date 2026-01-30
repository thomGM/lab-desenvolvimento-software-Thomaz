$(document).ready(function() {
    $("#calculoFrete").on("click", function() {
        $.ajax({
            url: urlCorreiosToken, // Usa a variável global definida no HTML
            type: "POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") // Adiciona CSRF token
            },
            dataType: "json",
            success: function(response) {
                console.log("Token recebido:", response);
            },
            error: function(xhr, status, error) {
                console.error("Erro na requisição:", xhr.responseText);
            }
        });
    });
});
