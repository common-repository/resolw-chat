=== Resolw Chat ===

Contributors: margusengso
Tags: resolw, resolw chat, customer support
Requires at least: 4.7
Tested up to: 5.7
Stable tag: 1.0.4
License: GPLv2 or later

Adds your Resolw Chat support room link on the webpage.

== Description ==

Efficiently organise your incoming customer requests with Resolw’s multifaceted customer support plugin. Integrate live chat, video and image pointing calls, and audio calls on your website with Resolw’s chat button.

= Better connections with your customers =
With Resolw’s plugin, your customers can open a live chat with you, directly from your website. From there, you can start a video pointing or audio call. You don’t need any training or technological expertise to start using Resolw with the whole team and your customers - you simply need a device with an internet connection!

= Provide quality remote support with video pointing =
Resolw helps you to gather accurate information by being able to see the customer’s issue via their phone’s rear-facing camera, and to draw on the screen to give live instructions. Invite your colleagues to the call if a second opinion is needed, and diagnose a problem remotely with your team. The customer doesn’t need to install any software to make this happen!

= Features =
Live chat 
Video pointing calls
Image pointing calls
Audio calls
Intra-team messaging
Unlimited support rooms, each with a unique contact link
Secure communication

= More information =
* Click [here](https://knowledgebase.resolw.com/help/wordpress-plugin) for details on installing the plugin, and changing your room links.
* Find out more about how to start using the Resolw app in our [Setup Guide](https://knowledgebase.resolw.com/help/setup-guide).
* You can sign up [on our website](https://resolw.com/signup/), or via our plugin, and enjoy the first 14 days free!


== Installation ==

Login to your Wordpress wp-admin account. Open Plugins section in the left sidebar menu. Select "Add New" and search for "Resolw Chat". Install and activate the plugin. After activation you should find the Resolw section in the left sidebar menu. From that section login to your Resolw Chat account and select the room you want to support your website with. Logging out from the account in that section removes the chatlink from your webpage.


== Screenshots ==

1. Support Chat link bubble on your webpage
2. Log in to your Resolw account
3. Select a support room link for the chat bubble
4. Search for the room you wish to select
5. Support room is selected


== Developer info ==

= REST-api =
The plugin relies on external [Resolw API](https://api.resolw.com/) for the purpose of your support rooms data retrieval. In order to retrieve the correct data from [Resolw API](https://api.resolw.com/) you are also required to log in to your Resolw account from the plugins administrative view. In order to use the API, one must have created a [Resolw account](https://app.resolw.com/signup) and agreed to our [terms of service](https://resolw.com/terms-of-service/) and our [privacy policy](https://resolw.com/privacy-policy/).
All REST-api requests are done server side. Your Resolw access key is associated with your wordpress user id and is never exposed to frontside javascript.
Several [Resolw API](https://api.resolw.com/) endpoints are used to support the plugin’s.
* GET user/me is used to verify the API key. 
* POST user/login is for regular email & password login. Upon successful login as a Team Owner or Administrator, the room selection view is loaded.
* GET support/units retrieves the list of support rooms belonging to the team of a logged in user.
No API requests are done by the site visitor/customer. Should you change your support room’s public link, the selected room data should be refreshed in the Resolw Wordpress plugin settings. Refresh can be achieved either by logging out and back in to your Resolw account from the Resolw Wordpress plugin, after which you should select that room again. Another method is to select a different room and then return to the former room.

= General file structure =
The main plugin file resolw_wp_plugin.php determines which scripts to load, whether you are logged in to the admin pages, or as a regular website visitor. All the administrative options (such as logging in to your Resolw account or changing the support room) are only available whilst logged in to /wp-admin.

The administrative files, main.php, login.php and units.php, are located in the resolw_wp_plugin/ root folder and provide functionality accordingly. The main.php file serves as a mere wrapper for both, in order to avoid banner from reloading. Once you have logged in, the units.php contains code for the room selection dropdown. Once you log out from the Resolw account the room selection is deleted from the database and the login.php content is loaded. Keep your Resolw account logged in after making the selection.

Javascript files are stored in the ./js folder of your Resolw plugin root. Both login.js and unit.js provide administrative functionality accordingly and are only loaded for the /wp-admin user. link_bubble.js provides chat link bubble creation and functionality for the support link if it is stored in the database. From that file the chat bubble is written straight to the DOM body element using createElement and appendChild methods. Generally, it’s composed of 3 svg images wrapped inside necessary div and span elements.

Inside the ./styles directory you will find two stylesheet files: admin.css and customer.css. admin.css is only for the Wordpress administrative views and customer.css is only for the chat link bubble.

= Chat bubble =
In order to change the chat bubble styles it is recommended to use a [child theme's](https://developer.wordpress.org/themes/advanced-topics/child-themes/) style sheet. It’s possible to edit the plugin chat link stylesheet at plugins/resolw_wp_plugin/styles/customer.css. However, it's not recommended to edit style sheets in the plugin because your changes will be overwritten when the plugin is updated.

Changing the link bubble colors

.resolwc_link-background-color {
    fill: #54BA65;
}
.resolwc_link-foreground-color {
    fill: #FFFFFF;
}
.resolwc_link-stripes-color {
    fill: #54BA65;
}


Changing the animation speed

The animated chat link bubble is divided into 3 parts with different animations in the customer.css stylesheet file: #resolwc_linkBackgroundElement, #resolwc_linkForegroundElement and #resolwc_stripes. The animations are named accordingly: resolwc_back, resolwc_front and resolwc_stripes.
Example:  
#resolwc_linkBackgroundElement {
  	...
}

@-webkit-keyframes resolwc_back {
  	...
}


Changing link location
The default location for the link is centered at the bottom of the page.
#resolwc_svg-bubble {
	...
}



== Changelog ==

= 1.0.4 =
* New Banner.

= 1.0.3 =
* Term Unit is renamed to Room. Minor style changes.

= 1.0.2 =
* Readme updates.

= 1.0.1 =
* Changed Chat bubble default location from bottom center to bottom right.

