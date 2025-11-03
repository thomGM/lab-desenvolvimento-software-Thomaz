/* jQuery-based menu controls */
(function($){
	'use strict';

	// hide the sidebar by adding the 'hidden' class
	window.desabilitar = function(){
		var $sidebar = $('#sidebar');
		if(!$sidebar.length) return;
		$sidebar.addClass('hidden');
	};

	// toggle sidebar visibility
	window.toggleSidebar = function(){
		var $sidebar = $('#sidebar');
		if(!$sidebar.length) return;
		$sidebar.toggleClass('hidden');
	};

	// optional: close sidebar when clicking on overlay/outside (uncomment if you add an overlay)
	// $(document).on('click', function(e){
	//     if($(e.target).closest('#sidebar').length === 0) $('#sidebar').addClass('hidden');
	// });

})(jQuery);
