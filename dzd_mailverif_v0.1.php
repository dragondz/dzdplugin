<?php

// This is a PLUGIN TEMPLATE.

// Copy this file to a new name like abc_myplugin.php.  Edit the code, then
// run this file at the command line to produce a plugin for distribution:
// $ php abc_myplugin.php > abc_myplugin-0.1.txt

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Plugin names should start with a three letter prefix which is
// unique and reserved for each plugin author ("abc" is just an example).
// Uncomment and edit this line to override:
$plugin['name'] = 'dzd_mailverif';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
# $plugin['allow_html_help'] = 1;

$plugin['version'] = '0.1';
$plugin['author'] = 'Messaoudi Rabah (Dragondz)';
$plugin['author_uri'] = 'http://info.ix-dz.com';
$plugin['description'] = 'Change mail content and store data on table';

// Plugin load order:
// The default value of 5 would fit most plugins, while for instance comment
// spam evaluators or URL redirectors would probably want to run earlier
// (1...4) to prepare the environment for everything else that follows.
// Values 6...9 should be considered for plugins which would work late.
// This order is user-overrideable.
$plugin['order'] = '6';

// Plugin 'type' defines where the plugin is loaded
// 0 = public       : only on the public side of the website (default)
// 1 = public+admin : on both the public and admin side
// 2 = library      : only when include_plugin() or require_plugin() is called
// 3 = admin        : only on the admin side
$plugin['type'] = '0';

// Plugin "flags" signal the presence of optional capabilities to the core plugin loader.
// Use an appropriately OR-ed combination of these flags.
// The four high-order bits 0xf000 are available for this plugin's private use
if (!defined('PLUGIN_HAS_PREFS')) define('PLUGIN_HAS_PREFS', 0x0001); // This plugin wants to receive "plugin_prefs.{$plugin['name']}" events
if (!defined('PLUGIN_LIFECYCLE_NOTIFY')) define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002); // This plugin wants to receive "plugin_lifecycle.{$plugin['name']}" events

$plugin['flags'] = '0';

if (!defined('txpinterface'))
        @include_once('zem_tpl.php');

# --- BEGIN PLUGIN CODE ---
/**
	Registers the callback. dzd_mailverif_function() is
	now loaded on 'zemcontact.submit' event. You can find
	the callback spot from ZRC's source and what it can offer.
*/

register_callback('dzd_mailverif','zemcontact.submit');

/**
	The function that does the work on
	the submit event
*/

function dzd_mailverif() {


	$evaluation =& get_zemcontact_evaluator();

	/*
		It's spam, end here
	*/

	if($evaluation->get_zemcontact_status() != 0)
		return;

	/*
		Saving the data goes here etc..
		$zem_contact_values global etc. can be
		used to get the data and so on.
	*/
	
	global $zem_contact_values;
	$set = 'timestamp=NOW()';
        $uid = md5(uniqid(rand(),true));
        $mytitle = stripSpace($zem_contact_values[nom], 1);
        $vowels = array(" ", ",", ".");
        $onlynumbers = str_replace($vowels, "", $zem_contact_values[montant]);
	
	$myid = safe_insert(
			   "textpattern",
			   "Title           = '$zem_contact_values[nom]',
				Body            = '$zem_contact_values[organisme]',
				Body_html       = '$zem_contact_values[organisme]',
				Status          =  '2',
				Posted          =  now(),
				AuthorID        = 'dons',
				LastMod         =  now(),
				LastModID       = 'dons',
				Section         = 'dons',
				url_title       = '$mytitle',
				custom_1        = '$zem_contact_values[Email]',
				custom_2        = '$onlynumbers',
				custom_3        = '$uid',
				custom_4        = '$zem_contact_values[visible]',
				custom_5        = '$zem_contact_values[adresse]',
				custom_6        = '$zem_contact_values[tel]',
				custom_7        = '$zem_contact_values[newsletter]',
				uid             = '$uid',
				feed_time       = now()"
			);
         if ($myid<>''){
            $zem_contact_values['lien'] = 'Cliquez sur ce lien pour valider votre don : http://darnadz.org/verif?mid='.$myid.'&myverif='.$uid.'';
            $zem_contact_values[montant] = $onlynumbers;
         }

}
# --- END PLUGIN CODE ---
if (0) {
?>
<!--
# --- BEGIN PLUGIN HELP ---

# --- END PLUGIN HELP ---
-->
<?php
}
?>
