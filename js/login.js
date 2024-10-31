var resolwc_team_name = '';
var resolwc_units = [];
var resolwc_login_loading = false;

jQuery( document ).ready(function() {
	if (document.getElementById('resolwc_main')) {
  		resolwc_check_login();
	}
});

function resolwc_check_login() {
	var data = {
		'action': 'resolwc_check_login'
	};
	jQuery.post(ajaxurl, data, function(response){
		var returned_data = JSON.parse(response);
		if (returned_data.status === "ok"){
			if (returned_data.proceed) {
				resolwc_load_units_component();
			} else {
				resolwc_load_login_component();
			}
		} else {
			resolwc_load_login_component();
			if (document.getElementById('resolwc_login_error')) {
				document.getElementById('resolwc_login_error').innerHTML = returned_data.message;
			} else {
				setTimeout(() => {
					document.getElementById('resolwc_login_error').innerHTML = returned_data.message;
				}, 3333);
			}
		}
	})
}

function resolwc_load_units() {
	return new Promise((resolve, reject) => {
		var data = {
			'action': 'resolwc_load_units'
		};
		jQuery.post(ajaxurl, data, function(response){
			resolve(response);
		});
	})
}

function resolwc_load_units_component() {
	resolwc_load_units().then(response => {
		var returned_data = JSON.parse(response);
		if (returned_data.status === "ok") {
			resolwc_team_name = returned_data.team_name;
			resolwc_units = returned_data.units;
			var data = {
				'action': 'resolwc_load_units_component'
			};
			jQuery.post(ajaxurl, data, function(resp){
				document.getElementById('resolwc_main').innerHTML = resp;
				if (document.getElementById('resolwc_units_list')) {
					resolwc_render_team();
				} else {
					setTimeout(() => {
						resolwc_render_team();
					}, 3333);
				}
			});
		} else {
			document.getElementById('resolwc_login_error').innerHTML = returned_data.message;
		}
	});
}

function resolwc_load_login_component() {
	var data = {
		'action': 'resolwc_load_login_component'
	};
	jQuery.post(ajaxurl, data, function(response) {
		document.getElementById('resolwc_main').innerHTML = response;
	});
}

function resolwc_passwordLogin() {
	if (!resolwc_login_loading) {
		resolwc_login_loading = true;
		jQuery('#resolwc_login-button').addClass('login-loading');
		jQuery('#resolwc_login-button').removeClass('login-not-loading');
		var email = document.getElementById('resolwc_login_email').value;
		var password = document.getElementById('resolwc_login_password').value;
		var data = {
			'action': 'resolwc_pwlogin',
			email,
			password
		};
		jQuery.post(ajaxurl, data, function(resp) {
			jQuery('#resolwc_login-button').addClass('login-not-loading');
			jQuery('#resolwc_login-button').removeClass('login-loading');
			var response = JSON.parse(resp);
			if (response.status === 'ok') {
				resolwc_load_units_component();
			} else {
				document.getElementById('resolwc_login_error').innerHTML = response.message;
			}
			resolwc_login_loading = false;
		})
	}
}