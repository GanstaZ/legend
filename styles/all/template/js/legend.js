$(function() { // Avoid conflicts with other libraries

'use strict';

$('.modal-trigger').click(function(e) {
	var mod_id = $(this).attr('data-modal');

	e.preventDefault();
	$('#' + mod_id).css({'display': 'block'});
	$('body').css({'overflow-y': 'hidden'}); //Prevent double scrollbar.
});

$('.close-modal, .modal-sandbox').click(function() {
	$('.modal').css({'display': 'none'});
	$('body').css({'overflow-y': 'auto'}); //Prevent double scrollbar.
});

}); // Avoid conflicts with other libraries
