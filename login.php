<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<div class="login-content-styles">
	<br>
	<form>
  		<input type="email" id="resolwc_login_email" name="email" autocomplete="off" value="" placeholder='<?php echo __("Email", "resolw-chat"); ?>'>
  		<input type="password" id="resolwc_login_password" name="password" autocomplete="off" value="" placeholder='<?php echo __("Password", "resolw-chat"); ?>'><br>
  	</form>
  	<button id='resolwc_login-button' class="login-button login-not-loading" onclick='resolwc_passwordLogin()'><?php echo __('Log In', 'resolw-chat'); ?></button>
  	<p class="error-standard" id='resolwc_login_error'></p>
	<div class="cursor-default account-question"><?php echo __("Do not have resolw account?", "resolw-chat"); ?></div>
	<a class="signup-anchor" href="https://app.resolw.com/signup" target="_blank" rel="noopener noreferrer"><?php echo __("Try for free 30 days", "resolw-chat"); ?></a>
</div>


