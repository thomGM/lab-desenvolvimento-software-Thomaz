$(document).ready(function() {
    $('#formulario').submit(function(e) {
        var camposVazios = $('.required').filter(function() {
            return $(this).val() === '';
        });

        if (camposVazios.length > 0) {
            e.preventDefault(); 
            camposVazios.css('border', '1px solid #ff0000'); 
            $("#mensagem").show();
        }
    });

    $('.required').keyup(function() {
        if ($(this).val() !== '') {
            $(this).css('border', ''); 
        }
    });

    $('#cpf').on('input', function() {
        var cpf = $(this).val().replace(/\D/g, '');
        if(cpf.length === 11) {
          cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        } else {
          cpf = cpf.replace(/(\d{0,3})(\d{0,3})(\d{0,3})(\d{0,2})/, '$1.$2.$3-$4').replace(/\-$/, '');
        }
        if (cpf.length < 14) {
            celular = celular.replace(/\./g, '').replace(/\(/g, '').replace(/\)/g, '');
        }
        $(this).val(cpf);
      });

    $('#celular').on('input', function(){
        var celular = $(this).val().replace(/\D/g, '');
        if(celular.length === 11) {
            celular = celular.replace(/(\d{2})(\d{1})(\d{4})(\d{4})/, '($1) $2 $3-$4');
        } else {
            celular = celular.replace(/(\d{0,2})(\d{0,1})(\d{0,4})(\d{0,4})/, '($1) $2 $3-$4').replace(/\-$/, '');
        }
        if (celular.length < 14) {
            celular = celular.replace(/\./g, '').replace(/\(/g, '').replace(/\)/g, '');
        }
        $(this).val(celular);
    });
});


