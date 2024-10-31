var resolwc_data = [];
jQuery( document ).ready(function() {
	resolwc_data = [];
});
function resolwc_render_team() {
	jQuery("#resolwc_team_name").html(resolwc_team_name);
	var supportUnits = resolwc_units;
	for (let i = 0; i < supportUnits.length; i++) {
		let dat = {};
		if (!supportUnits[i].imageLink) {
			var unitLetter = supportUnits[i].title.substr(0, 1).toUpperCase();
			supportUnits[i].imageLink = 'https://ui-avatars.com/api/?uppercase=false&background=00A562&color=FFFFFF&name=' + unitLetter + '&size=' + 56 + '&font-size=0.33&bold=true';
		}
		dat.imageLink = supportUnits[i].imageLink;
		dat.text = supportUnits[i].title;
		dat.id = supportUnits[i].unitId;
		dat.mainLinkText = supportUnits[i].mainLinkText;
		resolwc_data.push(dat);
	}

	jQuery("#resolwc_units_selection").select2({
		data: resolwc_data,
		templateResult: resolwc_FormatData
	});

	jQuery('#resolwc_units_selection').on('select2:select', function(event){
    	var unit_id = event.params.data.id;
    	var imageLink = event.params.data.imageLink;
    	var mainLinkText = event.params.data.mainLinkText;
    	var title = event.params.data.text;
    	resolwc_save_selected_unit(unit_id, imageLink, mainLinkText, title);
	});

	resolwc_get_initial_selected_unit(resolwc_data);
}

function resolwc_refresh() {
	var action_data = {
		'action': 'resolwc_get_selected_unit'
	};
	jQuery.post(ajaxurl, action_data, function(response) {
		for (let i = 0; i < reslwcht_data.length; i++) {
			if (resolwc_data[i].id === response) {
				resolwc_save_selected_unit(resolwc_data[i].id, resolwc_data[i].imageLink, resolwc_data[i].mainLinkText, resolwc_data[i].text);
			}
		}
	});
}

function resolwc_FormatData (data) {
  	if (!data.id) { return data.text; }
  	var $data = jQuery(
   '<div class="single_unit_row" value="'+data.id+'"><div class="unit-image-wrapper"><div style="background-image: url('+data.imageLink+')" class="unit-image"></div></div><div class="unit-label-wrapper"><div class="unit-label-grower"></div><div class="unit-label-shrinker">'+data.text+'</div><div class="unit-label-grower"></div></div>'
  	);
  	return $data;
 }

 function resolwc_add_image_to_selected(id) {
 	let el = document.getElementById('select2-resolwc_units_selection-container');
 	for (let i = 0; i < resolwc_data.length; i++) {
 		if (resolwc_data[i].id === id) {
 			el.innerHTML = '<div class="single_unit_row" value="'+resolwc_data.id+'"><div class="unit-image-wrapper"><div style="background-image: url('+resolwc_data[i].imageLink+')" class="unit-image"></div></div><div class="unit-label-wrapper"><div class="unit-label-grower"></div><div class="unit-label-shrinker">'+resolwc_data[i].text+'</div><div class="unit-label-grower"></div></div>';
 		}
 	}
 }

function resolwc_get_initial_selected_unit(data) {
	var data = {
		'action': 'resolwc_get_selected_unit'
	};
	jQuery.post(ajaxurl, data, function(response) {
		jQuery('#resolwc_units_selection').val(response).trigger('change');
		// resolwc_show_refresh(response);
			resolwc_finish_loading(); //
			resolwc_add_image_to_selected(response);
	});
}

function resolwc_finish_loading() {
	jQuery('#resolwc_loading').removeClass('show-me');
	jQuery('#resolwc_loading').addClass('remove-me');
	jQuery('#resolwc_unit-selection-content').removeClass('hide-me');
	jQuery('#resolwc_unit-selection-content').addClass('show-me');
}

/*function resolwc_show_refresh(id) {
	for (let i = 0; i < resolwc_data.length; i++) {
		if (resolwc_data[i].id === id) {
			jQuery('#resolwc_refresh-unit-button').removeClass('hide-me');
			jQuery('#resolwc_refresh-unit-button').addClass('show-me');
			i = resolwct_data.length;
		}
		if (i >= (resolwc_data.length - 1)){
			resolwc_finish_loading();
		}
	}
}*/

function resolwc_save_selected_unit(unit_id, imageLink, mainLinkText, title) {
	jQuery('#resolwc_refresh-unit-button').removeClass('show-me');
	jQuery('#resolwc_refresh-unit-button').addClass('remove-me');
	var data = {
		'action': 'resolwc_save_selected_unit',
		'unit_id': unit_id,
		'imageLink': imageLink,
		'mainLinkText': mainLinkText,
		'title': title
	};
	jQuery.post(ajaxurl, data, function(response) {
		if (response !== "ok") {
			document.getElementById('resolwc_unit_error').innerHTML = 'Error saving setting!';
		}
		resolwc_add_image_to_selected(unit_id);
	});
}

function resolwc_logout() {
	resolwc_data = [];
	return new Promise((resolve, reject) => {
		var data = {
			'action': 'resolwc_logout'
		};
		jQuery.post(ajaxurl, data, function(resp) {
			document.getElementById('resolwc_main').innerHTML = resp;
			resolve()
		});
	});
}
