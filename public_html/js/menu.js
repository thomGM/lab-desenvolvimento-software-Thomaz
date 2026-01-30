$(document).ready(function(){
    // Menu desktop
    $('#feminino').hover(function(){
        $('.submenu').toggle();
    });
    $('#masculino').hover(function(){
        $('.submenu1').toggle();
    });
    $('#conta').hover(function(){
        $('.submenu2').toggle();
    });

    // Menu mobile
    $('#menu-toggle').click(function() {
        $(this).toggleClass('active');
        $('#mobile-nav').toggleClass('active');
    });

    $('.menu-header').click(function() {
        const submenuId = $(this).data('submenu');
        const submenu = $('#submenu-' + submenuId);
        
        $(this).toggleClass('active');
        submenu.toggleClass('active');
        
        // Fecha outros submenus
        $('.menu-header').not(this).removeClass('active');
        $('.submenu-mobile').not(submenu).removeClass('active');
    });

    // Fecha menu ao clicar fora
    $(document).click(function(event) {
        if (!$(event.target).closest('.menu-mobile').length) {
            $('#menu-toggle').removeClass('active');
            $('#mobile-nav').removeClass('active');
            $('.menu-header').removeClass('active');
            $('.submenu-mobile').removeClass('active');
        }
    });

    // Hover effects
    $('.opcao, .conta, .menuOp').hover(
        function() {
            $(this).addClass('hover');
        },
        function() {
            $(this).removeClass('hover');
        }
    );
});
