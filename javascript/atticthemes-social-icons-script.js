//console.log('hello');


(function($) {
	'use strict';

	/* EDITOR ========================================================================================================= */
	/* ================================================================================================================ */
	/* ================================================================================================================ */
	/* ================================================================================================================ */

	$('.atticthemes-social-icon-sets-wrapper').on( 'click', 'li.atticthemes-social-icon-add-icon', openAdder );
	
	function applyDblClick() {
		$( '.atticthemes-social-icon-sets-wrapper' ).off( 'dblclick', 'li.atsi', openEditor );
		$( '.atticthemes-social-icon-sets-wrapper' ).on( 'dblclick', 'li.atsi', openEditor );
	}
	applyDblClick();


	function openEditor() {
		var icon = $(this);
		var editor = $('.atticthemes-social-icon-editor-wrapp');
			editor.find('.atticthemes-social-icon-editor-title').text( icon.attr('title') );
		var input = editor.find('input.atticthemes-social-icon-link-input');
			input.val( decodeURI(icon.attr('data-link')) );
			setTimeout(function() {
				input.focus().select();
			}, 50);

		var status_bar = editor.find('.atticthemes-social-icon-editor-status-bar');
			status_bar.text('');

		editor.data('icon', icon );
		editor.fadeIn();

		input.off( 'keyup' );
		input.on( 'keyup', function( e ) {
			e.preventDefault();
			if( (e.keyCode || e.which) == 13 ) {
				editor.find( '.atticthemes-social-icon-editor-done-button' ).trigger('click');
			} else if( (e.keyCode || e.which) == 27 ) {
				editor.find( '.atticthemes-social-icon-editor-cancel-button' ).trigger('click');
			}
			//console.log( e.keyCode, e.which );
		});
	}


	function openAdder() {
		var adder_button = $(this);
		var set = $('.atticthemes-social-icon-set-container').has( this );
		var set_id = set.attr('data-set-id');

		var adder = $('.atticthemes-social-icons-list-wrapp');
			adder.fadeIn('fast');

		adder.data('set_id', set_id );

		var closer = $( '.atticthemes-social-icons-list-close', adder );
			closer.one( 'click', closeAdder );

		function closeAdder() {
			adder.fadeOut('fast');
		}
	}


	$('.atticthemes-social-icons-list').on('click', 'li', function() {
		var icon = $(this);
		var new_icon = icon.clone().attr({
			'data-link': ''
		});

		var adder = $('.atticthemes-social-icons-list-wrapp');
		var set_id = adder.data('set_id');

		var set = $('.atticthemes-social-icon-set-container[data-set-id="'+set_id+'"]');
		var add_icon = $( '.atticthemes-social-icon-add-icon', set );
			add_icon.before( new_icon );

		var preloader = adder.find('.atticthemes-social-icons-list-preloader');
			preloader.fadeIn('fast');

		var is_new = set.hasClass('new-atticthemes-social-icon-set');
			set.removeClass('new-atticthemes-social-icon-set');
			if( is_new ) {
				updateIDs();
			}

		saveSets( function( response ) {
			if( response.status === 'success' || response.status === 'no-change' ) {
				preloader.fadeOut({
					duration: 200,
					complete: function() {
						console.log( 'icon added' );
					}
				});
			} else if( response.status === 'error' ) {
				preloader.fadeOut({duration: 200});
				$('.atticthemes-social-icons-list-status-bar').text( response.message );
			}
		});
		//console.log( set );
	});


	$('.atticthemes-social-icon-editor').on('click', '.atticthemes-social-icon-editor-cancel-button', function() {
		var editor = $('.atticthemes-social-icon-editor-wrapp');
		editor.fadeOut('fast');
	});

	$('.atticthemes-social-icon-editor').on('click', '.atticthemes-social-icon-editor-done-button', function() {
		var editor = $('.atticthemes-social-icon-editor-wrapp');
		var value = editor.find('input.atticthemes-social-icon-link-input').val();
		var id = editor.attr('data-icon-id');
		var icon = editor.data( 'icon' );

		var buttons = editor.find('button');
			buttons.attr('disabled', true);

		var preloader = editor.find('.atticthemes-social-icon-editor-preloader');
			preloader.fadeIn('fast');

		var status_bar = editor.find('.atticthemes-social-icon-editor-status-bar');

			icon.attr({ 'data-link': value });
		//console.log( value, icon );

		saveSets( function( response ) {
			if( response.status === 'success' || response.status === 'no-change' ) {
				preloader.fadeOut({
					duration: 200,
					complete: function() {
						editor.fadeOut({
							complete: function() {
								buttons.removeAttr( 'disabled' );
							}
						});

						if( value !== '' ) {
							icon.removeClass('no-link');
						} else {
							icon.addClass('no-link');
						}
					}
				});
			} else if( response.status === 'error' ) {
				preloader.fadeOut({duration: 200});
				$('.atticthemes-social-icon-set-id-editor-status-bar').text( response.message );
				//set.attr( 'data-set-id', set_id );
			}
		});

		//console.log('done');
	});





	/* SORTABLES & DREAGGABLES ======================================================================================== */
	/* ================================================================================================================ */
	/* ================================================================================================================ */
	/* ================================================================================================================ */


	$( '.atticthemes-social-icon-set-container' ).each(function() {
		var set = $(this);
		addSet( set );
		changed( set );
	}); //END each

	function addSet( set ) {
		$( '.atticthemes-social-icon-set', set ).sortable({
			connectWith: $('.atticthemes-social-icon-set-trash', set),
			items: 'li:not(.placeholder):not(.atticthemes-social-icon-add-icon)',
			revert: true,
			placeholder: 'ui-state-highlight',
			sort: function() {
				// gets added unintentionally by droppable interacting with sortable
				// using connectWithSortable fixes this, but doesn't allow you to customize active/hoverClass options
				$( this ).removeClass( 'ui-state-default' );

			},
			receive: function(event, ui) {
				var is_new = set.hasClass('new-atticthemes-social-icon-set');

				set.removeClass('new-atticthemes-social-icon-set');

				if( is_new ) {
					updateIDs();
				}
				//console.log('received', is_new);
				applyDblClick();
				//changed( set );
			},
			stop: function() {
				changed( set );
				saveSets();
			}
		}).disableSelection();

		$( '.atticthemes-social-icon-set-trash', set ).sortable({
			items: 'li:not(.placeholder):not(.atticthemes-social-icon-set-dummy-icon)',
			revert: true,
			receive: function(event, ui) {
				ui.item.fadeOut({
					duration: 150,
					complete: function() {
						$( '.atticthemes-social-icon-set-trash', set ).empty();
						var set_lis = $('.atticthemes-social-icon-set li', set).not('li.atticthemes-social-icon-add-icon').not('li.atticthemes-social-icon-set-dummy-icon');
						if( set_lis.length === 0 ) {
							setTimeout( function() {
								set.slideUp().fadeOut({
									complete: function() {
										set.remove();
									}
								});
							}, 50);
						} //END if confirm
					}
				});
			},
			over: function() {
				$( '.atticthemes-social-icon-set-trash', set ).addClass('over-trash');
			},
			out: function() {
				$( '.atticthemes-social-icon-set-trash', set ).removeClass('over-trash');
			}
		});

		$( '.atticthemes-social-icon-set-size', set ).on('change', function() {
			changed( set );
			saveSets();
		});

		set.find('.atticthemes-social-icon-set-shortcode-text').click(function() {
			$(this).select();
		});

		set.on('dblclick', '.atticthemes-social-icon-set-shortcode-text', function() {
			if( $('.atticthemes-social-icon-set li', set).not('.atticthemes-social-icon-set-dummy-icon').length > 0 ) {
				openIconSetIdEditor( set );
			} else {
				alert( atticthemes_social_icons.no_icons_in_set );
			}
		});
	}

	$('.atticthemes-social-icon-set-id-editor-cancel-button').on('click', function() {
		var editor = $('.atticthemes-social-icon-set-id-editor-wrapp');
		editor.fadeOut('fast');
	});

	$('.atticthemes-social-icon-set-id-editor-done-button').on('click', function() {
		var editor = $('.atticthemes-social-icon-set-id-editor-wrapp');
		var set_id = editor.attr('data-set-id');
		var set = $('.atticthemes-social-icon-set-container[data-set-id="'+ set_id +'"]');

		var input = editor.find('input.atticthemes-social-icon-set-id-link-input');

		
		var preloader = editor.find('.atticthemes-social-icon-set-id-editor-preloader');
			preloader.fadeIn('fast');

		set.attr( 'data-set-id', input.val() );

		saveSets( function( response ) {

			if( response.status === 'success' || response.status === 'no-change' ) {
				$('.atticthemes-social-icon-set-shortcode-text', set)
					.val('['+ atticthemes_social_icons.shortcode_tag +' set="'+ input.val() +'"]');
				preloader.fadeOut({
					duration: 200,
					complete: function() {
						editor.fadeOut('fast');
					}
				});
			} else if( response.status === 'error' ) {
				preloader.fadeOut({duration: 200});
				$('.atticthemes-social-icon-set-id-editor-status-bar').text( response.message );
				set.attr( 'data-set-id', set_id );
			}

		});
	});

	function openIconSetIdEditor( set ) {
		var editor = $('.atticthemes-social-icon-set-id-editor-wrapp');
		var input = editor.find('input.atticthemes-social-icon-set-id-link-input');
			input.val( set.attr('data-set-id') );

		var status_bar = editor.find('.atticthemes-social-icon-set-id-editor-status-bar');
			status_bar.text('');

		setTimeout(function(){
			input.focus().select();
		}, 50);

		editor.attr('data-set-id', set.attr('data-set-id') );
		editor.fadeIn('fast');

		input.off( 'keyup' );
		input.on( 'keyup', function( e ) {
			e.preventDefault();
			if( (e.keyCode || e.which) == 13 ) {
				editor.find( '.atticthemes-social-icon-set-id-editor-done-button' ).trigger('click');
			} else if( (e.keyCode || e.which) == 27 ) {
				editor.find( '.atticthemes-social-icon-set-id-editor-cancel-button' ).trigger('click');
			}
			//console.log( e.keyCode, e.which );
		});
	}
	
	function changed( set ) {
		/*var list = $( '.atticthemes-social-icon-set li', set ).not('li.ui-state-highlight');
		var shortcode_text = set.find('.atticthemes-social-icon-set-shortcode-text');
		var icons = [];
		var size = $( '.atticthemes-social-icon-set-size', set ).val();

		list.each(function() {
			icons.push( $(this).attr('data-icon-id') );
		});

		var shortcode = '[atticthemes_social set="'+ set_id_counter +'"]';
		
		shortcode_text.val( shortcode );

		//console.log('changed', shortcode);*/
	}

	function saveSets( callback ) {
		var sets = {};

		$('.atticthemes-social-icon-set-container').not('.new-atticthemes-social-icon-set').each(function() {
			var set = $(this);
			var icons = [];
			var size = '';

			var icon_lis = set.find('.atticthemes-social-icon-set>li')
				.not('li.ui-state-highlight')
				.not('li.atticthemes-social-icon-set-dummy-icon')
				.not('li.atticthemes-social-icon-add-icon');

			icon_lis.each(function() {
				icons.push({
					'id': $(this).attr('data-icon'),
					'link': encodeURI( $(this).attr('data-link') ) || null,
				});
			});

			size = $('.atticthemes-social-icon-set-size', set).val();
			var set_id = set.attr('data-set-id');
			sets[set_id] = {
				icons: icons,
				size: size
			};
		});

		//console.log( sets );

		if( !$.isEmptyObject(sets) ) {
			//console.log(JSON.stringify(sets) );
			$.ajax({
				type : 'post',
				dataType : 'json',
				url : atticthemes_social_icons.ajax_url,
				data : {
					action: 'atticthemes_social_icon_save_set', 
					nonce: atticthemes_social_icons.ajax_nonce,
					data: btoa( JSON.stringify( sets ) )
				},
				success: function( response ) {
					//console.log( 'saveSets', response );

					if( callback ) {
						callback( response );
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('saveSets', jqXHR, textStatus, errorThrown);
				}
			});
		}
		//console.log('saving', sets);
	}

	function newSet( ID ) {
		var set = $('<div/>').attr({
			'data-set-id': 'icon-set-' + ID
		}).addClass('atticthemes-social-icon-set-container new-atticthemes-social-icon-set');
		$('<ul/>').addClass('atticthemes-social-icon-set-trash').appendTo( set );

		var set_ul = $('<ul/>').addClass('atticthemes-social-icon-set').appendTo( set );

			$('<li/>').addClass('atticthemes-social-icon-set-dummy-icon').appendTo( set_ul );
			$('<li/>').addClass('atticthemes-social-icon-add-icon dashicons dashicons-plus').appendTo( set_ul );

		var shortcode = $('<div/>').addClass('atticthemes-social-icon-set-shortcode').appendTo( set );
		var shortcode_input = $('<input/>').attr({'readonly': true, 'type': 'text'}).addClass('atticthemes-social-icon-set-shortcode-text').appendTo( shortcode );

		shortcode_input.val('['+ atticthemes_social_icons.shortcode_tag +' set="'+ ('icon-set-'+ID) +'"]');

		var select = $('<select/>').addClass('atticthemes-social-icon-set-size').appendTo( shortcode );
			
		if( window.atticthemes_social_icons && atticthemes_social_icons.icon_sizes ) {
			$.each(atticthemes_social_icons.icon_sizes, function( index, item ) {
				$('<option/>').attr('value', item.size).text(item.name).appendTo( select );
			});
		}


		set.hide().appendTo( $('.atticthemes-social-icon-sets-wrapper') ).fadeIn('fast');
		
		return set;
	}

	updateIDs( true );


	function updateIDs( dont ) {
		$('.atticthemes-social-icons-set-preloader').fadeIn('fast');
		$.ajax({
			type : 'post',
			dataType : 'json',
			url : atticthemes_social_icons.ajax_url,
			data : {
				action: 'atticthemes_social_icon_increment_ids',
				nonce: atticthemes_social_icons.ajax_nonce,
				increment: dont === undefined ? true : false
			},
			success: function(response) {
				//console.log( 'updateIDs', response );

				$('.atticthemes-social-icons-set-preloader').fadeOut({
					duration: 150,
					complete: function() {
						var new_set = new newSet( response.ID ? response.ID : 0 );
						addSet( new_set );
					}
				});

			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log('updateIDs', jqXHR, textStatus, errorThrown);
			}
		});
	}

})(jQuery);