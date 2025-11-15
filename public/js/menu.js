/* jQuery-based menu controls */
(function($){
    'use strict';
    
    $('.submenu').hide();

    $(document).on('click', '.submenu-trigger', function(e){
        e.preventDefault();
        $(this).parent().find('.submenu').slideToggle();
        $(this).parent().toggleClass('active');
    });

	window.desabilitar = function(){
		var $sidebar = $('#sidebar');
		if(!$sidebar.length) return;
		$sidebar.addClass('hidden');
		$(document.body).addClass('sidebar-collapsed');
		$sidebar.attr('aria-hidden', 'true');
	};

	window.toggleSidebar = function(){
		var $sidebar = $('#sidebar');
		if(!$sidebar.length) return;
		$sidebar.toggleClass('hidden');
		$(document.body).toggleClass('sidebar-collapsed');
		var hidden = $sidebar.hasClass('hidden');
		$sidebar.attr('aria-hidden', hidden ? 'true' : 'false');
	};
})(jQuery);
