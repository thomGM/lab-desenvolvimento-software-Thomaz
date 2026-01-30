<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Angel</title>
    </head>
    <body topmargin="0" leftmargin="0">
        <header>
            @include('components.menu')
        </header>
    </body>
</html>
