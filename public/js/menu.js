/* jQuery-based menu controls */
(function($){
    'use strict';
    
    // Controla os cliques no submenu
    $(document).on('click', '.submenu-trigger', function(e){
        e.preventDefault();
        $(this).parent().find('.submenu').slideToggle();
        $(this).parent().toggleClass('active');
    });

	// hide the sidebar by adding the 'hidden' class
	window.desabilitar = function(){
		var $sidebar = $('#sidebar');
		if(!$sidebar.length) return;
		$sidebar.addClass('hidden');
		// also add class on body to allow broader styling when needed
		$(document.body).addClass('sidebar-collapsed');
		$sidebar.attr('aria-hidden', 'true');
	};

	// toggle sidebar visibility
	window.toggleSidebar = function(){
		var $sidebar = $('#sidebar');
		if(!$sidebar.length) return;
		$sidebar.toggleClass('hidden');
		$(document.body).toggleClass('sidebar-collapsed');
		// update aria attribute
		var hidden = $sidebar.hasClass('hidden');
		$sidebar.attr('aria-hidden', hidden ? 'true' : 'false');
	};
})(jQuery);
