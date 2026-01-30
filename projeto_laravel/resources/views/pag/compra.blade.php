<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/compra.css') }}" rel="stylesheet">
    <title></title>
</head>
<body topmargin="0" leftmargin="0">
    <header>
        @include('components.menu')
    </header>
    @foreach($data as $data)
        <div class="divpai">
            <img src="{{ asset('storage/img/' . $data->img) }}" alt="img" class="img"></br>
        </div>
        <div class="texto">
            <span class="descricao">{{$data->descricao}}</span></br>
            <span class="valor">{{$data->descricao}}</span></br>
            <span class="descricaoRoupa">{{ $data->descricaoDetalhada }}</span>
        @php
            $tamanho = explode(',', $data->tamanho);
            $cores = explode(',', $data->cores);
        @endphp
    @endforeach
        <form name="submitCompra">
        @csrf
            <select name="tamanho">
                @foreach($tamanho as $tam)
                    <option value="{{$tam}}">{{strtoupper($tam)}}</option>
                @endforeach
            </select> 
            <select name="cor">
                @foreach($cores as $cor)
                    <option value="{{$cor}}">{{$cor}}</option>
                @endforeach
            </select>
            <input type="hidden" name="id_roupa" value="{{$data->id_roupa}}">
            <div class="divfilho">
                <button type="submit" id="comprar" class="botao">Comprar</button>
            </div>
        </form>
        </div>
    <script src="{{ asset('assets/jquery.js') }}"></script>
    <script src="{{ asset('assets/jquery_form.js') }}"></script>
    <script src="{{ asset('assets/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/compra.js') }}"></script>
    <script>
        
        $(document).ready(function() {
            $('form[name="submitCompra"]').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    url: "{{ route('carrinho.verifyLogin') }}",
                    type: "post",
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 1) {
                            console.log(data.messagem);
                            window.location.href = "{{ route('carrinho.adicionar') }}";
                        } else {
                            console.log(data.messagem);
                            
                            const novaDiv = $('<div class="dialogo" id="meu-dialogo"></div>');
                            novaDiv.addClass('border');
                            $('body').append(novaDiv);

                            $("#meu-dialogo").load('/pag.dialoglogin').dialog({
                                height: $(window).height() < 400 ? $(window).height() * 0.8 : 285, 
                                width: $(window).width() < 500 ? $(window).width() * 0.8 : 400,
                                modal: true,
                                closeOnEscape: true,
                                open: function(event, ui) {
                                    // Esconder o botão de fechar padrão
                                    $(this).parent().children('.ui-dialog-titlebar').children('.ui-dialog-titlebar-close').hide();
                                },
                                create: function(event, ui) {
                                    // Adicionar o botão "X" ao título
                                    var titlebar = $(this).parent().children('.ui-dialog-titlebar');
                                    titlebar.append('<button id="custom-close" style="position:absolute; right:3px; top:13px; color:black; background:rgb(163, 158, 158); border:none; font-size:18px; width:10%;">X</button>');

                                    // Fechar o diálogo ao clicar no "X"
                                    $('#custom-close').on('click', function() {
                                        $('#meu-dialogo').dialog('close');
                                    });
                                }
                            });
                        }
                    }
                });
            });               
        });
    </script>
</body>
</html>