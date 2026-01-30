<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/addFotos.css') }}" rel="stylesheet">
    <title>Document</title>
    <!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select com Checkboxes</title>
    <style>
        .custom-select {
            position: relative;
            width: 200px;
            border: 1px solid #ccc;
            background: #fff;
            cursor: pointer;
            padding: 8px;
            text-align: left;
        }

        .options {
            display: none;
            position: absolute;
            width: 100%;
            border: 1px solid #ccc;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 10;
            max-height: 200px;
            overflow-y: auto;
        }

        .option {
            padding: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .option:hover {
            background: #f0f0f0;
        }

        input[type="checkbox"] {
            cursor: pointer;
        }
    </style>
</head>
<body  topmargin="0" leftmargin="0">
    <header>
        @include('components.menu')
    </header>
<div class="container">
    <h1> Incluir novas fotos </h1></br>

    <form method="POST" enctype="multipart/form-data" action="{{ route('roupas') }}">
    @csrf
        <strong>Modulo</strong></br>
        <select name="modulo" id="modulo"  class = "select">
            <option value="1">FEMININO</option>
            <option value="2">MASCULINO</option>
            <option value="3">CALÇADOS/ACES.</option>
            <option value="4">LANÇAMENTOS</option>
            <option value="5">OFERTAS</option>
        </select></br>
        <strong>Tipo</strong></br>
        <select name="tipo" id="tipo"  class = "select">
            <option value="0">SELECIONE</option>
            <option value="1">CAMISAS</option>
            <option value="2">CALÇA</option>
            <option value="3">CASACO</option>
        </select></br>
        <strong>Genêro</strong></br>
        <select name="genero" id="genero" class="select">
            <option value="1">FEMININO</option>
            <option value="2">MASCULINO</option>
        </select></br>
        <strong>Descrição</strong></br>
        <textarea id="texto" name="texto" rows="4" cols="50"></textarea></br>
        <strong>Descrição Detalhada</strong></br>
        <textarea id="textoDetalhado" name="textoDetalhado" rows="4" cols="50"></textarea></br>
        <strong> Anexar foto </strong></br>
        <input type="file" name="foto" id="foto"></br></br>
        <strong>Cores</strong></br>
        <div class="custom-select" id="customSelect">
            Selecione as cores
            <div class="options" id="optionsList">
                <label class="option">
                    <input type="checkbox" id="all"/> Selecionar todos
                </label>
                <label class="option">
                    <input name="cor[]" class="cor" type="checkbox" value="Branco"/> Branco
                </label>
                <label class="option">
                    <input name="cor[]" class="cor" type="checkbox" value="Preto"/> Preto
                </label>
                <label class="option">
                    <input name="cor[]" class="cor" type="checkbox" value="Vermelho"/> Vermelho
                </label>
                <label class="option">
                    <input name="cor[]" class="cor" type="checkbox" value="Azul"/> Azul
                </label>
                <label class="option">
                    <input name="cor[]" class="cor" type="checkbox" value="Cinza"/> Cinza
                </label>
                <label class="option">
                    <input name="cor[]" class="cor" type="checkbox" value="Marron"/> Marron
                </label>
                <label class="option">
                    <input name="cor[]" class="cor" type="checkbox" value="Amarelo"/> Amarelo
                </label>
                <label class="option">
                    <input name="cor[]" class="cor" type="checkbox" value="Verde"/> Verde
                </label>
                <label class="option">
                    <input name="cor[]" class="cor" type="checkbox" value="Laranja"/> Laranja
                </label>
                <label class="option">
                    <input name="cor[]" class="cor" type="checkbox" value="Roxo"/> Roxo
                </label>
                <label class="option">
                    <input name="cor[]" class="cor" type="checkbox" value="Rosa"/> Rosa
                </label>
            </div>
        </div> </br></br>

        <strong>Tamanhos</strong> </br>
        <div class="custom-select" id="customSelectTamanho">
            Selecione os Tamanhos
            <div class="options" id="optionsTamanhos">
                <label class="option">
                    <input type="checkbox" id="alltamanho"/> Selecionar todos
                </label>
                <label class="option">
                    <input name="tamanho[]" class="tamanho" type="checkbox" value="p"/> P
                </label>
                <label class="option">
                    <input name="tamanho[]" class="tamanho" type="checkbox" value="m"/> M
                </label>
                <label class="option">
                    <input name="tamanho[]" class="tamanho" type="checkbox" value="g"/> G
                </label>
                <label class="option">
                    <input name="tamanho[]" class="tamanho" type="checkbox" value="gg"/> GG
                </label>
                <label class="option">
                    <input name="tamanho[]" class="tamanho" type="checkbox" value="xg"/> XG
                </label>
            </div>
        </div> </br></br>

            <strong>Quantidade de peças</strong> </br>
            <input type="number" name="quantidade" id="quantidade" class="select"/>
            </br></br>

            <strong>Valor</strong> </br>
            <input type="text" name="valor" id="valor" class="select"/>

            </br></br>

        <button type="submit" id="submit">Salvar</button>
    </form>
</div>

<script src="{{ asset('assets/jquery.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#submit').click(function(event) {
            var quant = $('#quantidade').val();
            if (quant > 0) {
                var tamanho = $('.tamanho:checked').length;
                var cor = $('.cor:checked').length;

                if(tamanho > 1 || cor > 1) {
                    event.preventDefault();
                    alert('Ao escolher a quantidade da peça, escolha apenas um tamanho e uma cor');
                }
            }
        });

        // "Selecionar Todos" marca ou desmarca todas as opções
        $("#all").change(function () {
            $(".cor").prop("checked", $(this).prop("checked"));
        });
        // Atualizar "Selecionar Todos" quando as opções individuais forem alteradas
        $(".cor").change(function () {
            $("#all").prop($(".cor:checked").length === $(".cor").length);
        });
        $("#customSelect").click(function (event) {
            event.stopPropagation(); 
            $("#optionsList").toggle(); 
        });
        $("#optionsList").click(function (event) {
            event.stopPropagation(); // Garante que clicar na lista não feche o menu
        });
        // 3. Fecha ao clicar em qualquer lugar FORA
        $(document).click(function () {
            $("#optionsList").hide();
        });
        
        $("#customSelectTamanho").click(function (event) {
            event.stopPropagation(); 
            $("#optionsTamanhos").toggle(); 
        });
        $("#optionsTamanhos").click(function (event) {
            event.stopPropagation(); // Garante que clicar na lista não feche o menu
        });

        $("#alltamanho").change(function () {
            $(".tamanho").prop("checked", $(this).prop("checked"));
        });

        $(".tamanho").change(function () {
            $("#alltamanho").prop($(".tamanho:checked").length === $(".tamanho").length);
        });

        $(document).click(function (event) {
            if (!$(event.target).closest("#customSelectTamanho").length) {
                $("#optionsTamanhos").hide();
            }
        }); 
    });
</script>
</body>
</html>