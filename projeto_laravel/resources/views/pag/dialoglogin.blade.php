<!DOCTYPE html>
<head>
    <link href="{{ asset('/css/dialogLogin.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="borda">
        <div class="text marginTop">
            E-mail
        </div>
        <div>
            <input type="text" name="email" id="email"/>
        </div>
        <div class="text">
            Senha
        </div>
        <div>
            <input type="password" name="senha" id="senha" placeholder="Senha"/>
            <span id="togglePassword" class="eye-icon">
                <i class="fas fa-eye"></i> <!-- Ícone do olho -->
            </span>        </div>
        <div class="erro"></div>
        <div class="entrar">
            <button class="margin" id="entrar">
                Entrar
            </button>
        </div>
        <div class="cadastro">
            Você não tem uma conta? <button id="cadastro"> Cadastre-se </button>
        </div>
</div>
<script>
        $("#entrar").on('click', function(event) {
            console.log('estou clicando');
            event.preventDefault();

            var login = $('#email').val();
            var senha = $('#senha').val();
            var local = 'dialogLogin';

            var dados = {
                email: login,
                senha: senha,
                local: local,
                token: $('meta[name="csrf-token"]').attr('content')
            }

           // console.log(dados);
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('login') }}",
                type: "post",
                data: JSON.stringify(dados),
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if (data.status == 1) {
                        console.log(data.mensagem);
                        $("#meu-dialogo").dialog("close");
                        window.location.href = "{{ route('carrinho.adicionar') }}";
                    } else {
                        if ($('.mensagem_erro').length > 0) {
                            $('.mensagem_erro').remove();
                        }
                        $(".erro").append("<div class='mensagem_erro'>"+data.mensagem+"</div>");
                       // $("#meu-dialogo").dialog("close");
                    }
                }
            });
        });

        $("#cadastro").on('click', function(event) {
            window.location.href = "{{ route('pag.cadastro') }}";
        });

        $(document).ready(function() {
            $('#togglePassword').click(function() {
                var passwordField = $('#senha');
                var icon = $(this).find('i');

                // Alterna o tipo do campo de senha para texto
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');  // Altera o ícone
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');  // Altera o ícone
                }
            });
        });
    </script>
</body>
