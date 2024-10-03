jQuery(document).ready( function($) {

	hcc_open_pointer(0);

	function hcc_open_pointer(i) {

		pointer = hccPointer.pointers[i];

		options = $.extend( pointer.options, {
			close: function() {

				$.post( ajaxurl, {
					pointer: pointer.pointer_id,
					action: 'dismiss-wp-pointer'
				});

				if( i == 0 )
				{
					$('#gb-hcc-post-type').val('page').trigger('change');
				}

				if( i == 7 )
				{
					$('#wc-hcc-save-btn').trigger('click');

					return;
				}

				if( ( i + 1 ) < hccPointer.pointers.length )
				{
					hcc_open_pointer( ( i + 1 ) );
				}
			}
		});

		$(pointer.target).pointer( options ).pointer('open');

		if( $("#wp-pointer-" + i ).length > 0 )
		{
			$('html, body').animate({
				scrollTop: $("#wp-pointer-" + i ).offset().top - 50
			}, 1000);
		}
	}
});