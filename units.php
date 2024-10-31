<?php if (!defined('ABSPATH')) {
    exit;
} ?>

<div id="resolwc_units">
	<span id="resolwc_loading" class="show-me content-styles">
		<div class="loader"></div>
	</span>
	<div id="resolwc_unit-selection-content" class="hide-me content-styles">
		<div class="team-name-row cursor-default team-name-label"><?php echo __("Resolw team name", "resolw-chat"); ?>: </div>
		<div class="team-name-row cursor-default team-name-text" id="resolwc_team_name"></div>

		<div class="cursor-default selector-label"><?php echo __("Active support room for chat", "resolw-chat"); ?></div>

		<select id="resolwc_units_selection" class="js-example-templating form-control resolwc_units_list_selecter">
		</select>
		<button onclick="resolwc_refresh()" id="resolwc_refresh-unit-button" class="hide-me cursor-pointer"><?php echo __("Refresh", "resolw-chat"); ?></button>

		<br>
		<br>
		<br>
		<p class="error-standard cursor-default" id='resolwc_unit_error'></p>
		<button class="logout-button cursor-pointer" onclick='resolwc_logout()'><?php echo __('Log out', 'resolw-chat'); ?></button>
	</div>
</div>