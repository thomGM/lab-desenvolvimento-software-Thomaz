<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/carrinho.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/fotos.css') }}" rel="stylesheet">
    <link href="{{ asset('js/carrinho.js') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <script>
        var urlCorreiosToken = "{{ route('correios.token') }}";
    </script>
</head>
<body topmargin="0" leftmargin="0" width="100%">
    <header>
        @include('components.menu')
    </header>
        <div class="minha_sacola">
            <strong> <span class="material-icons">shopping_cart</span> MINHA SACOLA </strong>
        </div>
        @if (!empty($roupas))
        <div class="pai">
            <div class="border">
                <table width="100%">
                    <tr>
                        <th align="center" width="40%">PRODUTO</th>
                        <th align="right" width="10%">CARACTERISTICAS</th>
                        <th align="right" width="10%">QUANTIDADE</th>
                        <th align="right" width="10%">TOTAL</th>
                        <th width="5%"></th>
                    </tr>
                    @foreach ($roupas as $roupa)
                    <tr id="linha_{{ $roupa->id_carrinho }}">
                        <td align="left">
                            <table width="100%">
                                <tr>
                                    <td colspan="2" rowspan="2" style="padding: 2% 2% 2% 10%;">                       
                                        <a href="{{ route('roupas.compra', ['id' => $roupa->id_roupa]) }}">
                                            <img class="foto" src="{{ asset('storage/img/' . $roupa->img) }}" alt="img" width="120" height="180">
                                         </a>                                    
                                    </td>
                                    <td></td>
                                    <td>
                                        {{ $roupa->descricao }}    
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <div id="tamanhoText_{{ $roupa->id_carrinho }}"> Tamanho: {{strtoupper($roupa->tamanho)}} <div>
                                        <div id="cor_{{ $roupa->id_carrinho }}"> Cor: {{$roupa->cor}} <div> 
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </td>
                        @php
                            $tamanhosArray = explode(',', strtoupper($roupa->tamanho_todos));
                        @endphp
                            <td align="center">
                                <select id="tamanho_{{ $roupa->id_carrinho }}" name="outro_tamanho">
                                    @foreach($tamanhosArray as $tamanho)
                                    <option value="{{ $tamanho }}" @if(strtoupper($roupa->tamanho) == $tamanho) selected @endif> {{ $tamanho }}</option>                                   
                                    @endforeach
                                </select>
                            </td>
                        <td align="center">
                            <input type="number" style="width: 30%; height: 100%; text-align: center;" min="1" value="{{$roupa->quantidade}}" id="quantidade_{{$roupa->id_carrinho}}" name="quantidade"/>
                        </td>
                        <td align="right">
                            <div id="valor_{{$roupa->id_carrinho}}">{{ $roupa->valor }}</div>
                            <input type="hidden" id="val_{{$roupa->id_carrinho}}" value="{{ $roupa->valor }}"/>
                            <input type="hidden" id="val1_{{$roupa->id_carrinho}}" name="val" value="{{ $roupa->valor }}"/>
                        </td>
                        <td align="right"><input type="button" id="excluir_{{$roupa->id_carrinho}}" class="excluir" name="excluir" value="X"/></td>
                    </tr>
                    @endforeach
                </table>
            </div>
            <div class="border1">
                @foreach ($roupas as $roupa)
                <div id="linha1_{{ $roupa->id_carrinho }}">
                    <div class="inline larguraIn">
                        <a href="{{ route('roupas.compra', ['id' => $roupa->id_roupa]) }}">
                            <img class="foto" src="{{ asset('storage/img/' . $roupa->img) }}" alt="img" width="120" height="180">
                        </a>
                    </div>
                    <div class="inline larguraIn">
                        <div class="margin1">{{ $roupa->descricao }}</div>
                        <div class="margin1" id="tamanhoText1_{{ $roupa->id_carrinho }}"> Tamanho: <strong>{{ strtoupper($roupa->tamanho) }}</strong></div>
                        <div class="margin1" id="cor_{{ $roupa->id_carrinho }}"> Cor: <strong>{{$roupa->cor}}</strong></div>
                        <div class="margin1" id="valor1_{{$roupa->id_carrinho}}">Valor: <strong>{{ $roupa->valor }}</strong></div>
                        <div class="inline margin1">
                            <select id="tamanho_{{ $roupa->id_carrinho }}" name="outro_tamanho">
                                    @foreach($tamanhosArray as $tamanho)
                                    <option value="{{ $tamanho }}" @if(strtoupper($roupa->tamanho) == $tamanho) selected @endif> {{ $tamanho }}</option>                                   
                                    @endforeach
                            </select>
                        </div>
                        <div class="inline largura margin1"> 
                            <input type="number" style="width: 100%; height: 100%; text-align: center;" min="1" value="{{$roupa->quantidade}}" id="quantidade_{{$roupa->id_carrinho}}" name="quantidade"/>
                        </div>
                        <div>
                            <input type="button" id="excluir_{{$roupa->id_carrinho}}" class="excluir" name="excluir" value="Excluir"/>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="border2">
                <div class="resumo">RESUMO DA COMPRA</div>
                <hr>
                <div class="margin">Cupom de desconto</div>
                <div  class="flex">
                    <input type="text" class="cupon" name="cupon" id="cupom"/>
                    <button class="buttonOk">OK</button>
                </div>
                <div class="margin">Calculo de Frete</div>
                <div  class="flex">
                    <input type="text" class="cupon" name="cep" id="cep"/>
                    <button class="buttonOk" id="calculoFrete">Calcular</button>
                </div>
                <div class="margin">
                    Descontos <input type="hidden" id="descontos"/>
                </div>
                <div class="margin">
                    Frete <input type="hidden" id="frete"/>
                </div>
                <div class="margin">
                    Total: <strong id="total"></strong>
                </div>
                <div class="flex">
                    <button class="botaoAltura">Finalizar Compra</button>
                </div>
                <div class="flex">
                    <button class="botaoAltura" id="continuar"> Continuar comprando </button>
                </div>
            </div>
        </div>
        @endif
    <script src="{{ asset('assets/jquery.js') }}"></script>
    <script src="{{ asset('js/carrinho.js') }}"></script>
    <script>

        function valores() {
            var total = 0.00;
            $("input[name='val']").each(function() {
                let id = $(this).attr('id').split('_')[1];
                var quantidade = parseFloat($('#quantidade_' + id).val());
                var valor_peca = parseFloat($('#val_' + id).val());

                total = quantidade * valor_peca;
                $('#valor_' + id).text(total);
                $('#valor1_' + id).html('');
                $('#valor1_' + id).html('Valor: <strong>'+total+'</strong>');
                $('#val1_' + id).val(total);
            });
        }
        function soma(excluido) {
            var valor = 0.00;
            $("input[name='val']").each(function() {
                valor += parseFloat($(this).val()) || 0.00;
            });
            valor_final = valor - parseFloat(excluido);

            $("#total").html(valor_final.toFixed(2));
        }

        $('input[name="quantidade"]').on('blur', function() {
        var quantidadeInput = $(this);
        var valorCampo1 = parseFloat(quantidadeInput.val());

        var itemId = quantidadeInput.attr('id').split('_')[1];
        var valorCampo2 = parseFloat($('#val_' + itemId).val());

        if (!isNaN(valorCampo1) && !isNaN(valorCampo2)) {
            var resultado = (valorCampo1 * valorCampo2).toFixed(2);

            var dados = {
                id: itemId,
                quant: valorCampo1
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('carrinho.quant') }}",
                type: "post",
                data: JSON.stringify(dados),
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        $('#valor_' + itemId).text(resultado);
                        $('#val1_' + itemId).val(resultado);
                        $('#valor1_' + itemId).html('');
                        $('#valor1_' + itemId).html('Valor: <strong>' + resultado + '</strong>');
                        soma(0);
                    } else {
                        console.log(data.messagem);
                    }
                }
            });
        }
    });

        $('select[name="outro_tamanho"]').on('blur', function() {
            var tamanho = $(this);
            var id = tamanho.attr('id').split('_')[1];
            var tam = tamanho.val();
            console.log(tam);

            var dados = {
                id: id,
                tamanho: tam
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('carrinho.tamanho') }}",
                type: "post",
                data: JSON.stringify(dados),
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        $("#tamanhoText_"+id).text('');
                        $("#tamanhoText_"+id).html('Tamanho: ' + tam);
                        $("#tamanhoText_"+id).text('');
                        $("#tamanhoText1_"+id).html('Tamanho: <strong>' + tam + '</strong>');
                    } else {
                        console.log(data.messagem);
                    }
                }
            });
        });


        $('input[name="excluir"]').on('click', function() {
            console.log('click');
        var excluir = $(this);
        var id = excluir.attr('id').split('_')[1];
        var linha = "linha_"+id;
        var linha1 = "linha1_"+id;
        var valor = $("#valor_"+id).html();
       
        var dataExcluir = {
            id: id
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('carrinho.excluir') }}",
            type: "post",
            data: JSON.stringify(dataExcluir),
            dataType: 'json',
            success: function(data) {
                if (data.status == 1) {
                    soma(valor);
                    $("#" + linha).hide();
                    $("#" + linha1).hide();
                } else {
                    console.log(data.messagem);
                }
            }
        });
    });
        $(document).ready(function() {
            valores();
            soma(0);
        });

        $("#continuar").on('click', function(){
            window.location.href = "{{ route('welcome') }}";
        })
    </script>
</body>
</html>