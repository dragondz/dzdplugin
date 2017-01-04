<?php

// This is a PLUGIN TEMPLATE for Textpattern CMS.

// Copy this file to a new name like abc_myplugin.php.  Edit the code, then
// run this file at the command line to produce a plugin for distribution:
// $ php abc_myplugin.php > abc_myplugin-0.1.txt

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Plugin names should start with a three letter prefix which is
// unique and reserved for each plugin author ("abc" is just an example).
// Uncomment and edit this line to override:
$plugin['name'] = 'dzd_mailtodb';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
# $plugin['allow_html_help'] = 1;

$plugin['version'] = '0.1';
$plugin['author'] = 'Messaoudi Rabah (Dragondz)';
$plugin['author_uri'] = 'http://info.ix-dz.com';
$plugin['description'] = 'Store mail content on DB table';

// Plugin load order:
// The default value of 5 would fit most plugins, while for instance comment
// spam evaluators or URL redirectors would probably want to run earlier
// (1...4) to prepare the environment for everything else that follows.
// Values 6...9 should be considered for plugins which would work late.
// This order is user-overrideable.
$plugin['order'] = '6';

// Plugin 'type' defines where the plugin is loaded
// 0 = public              : only on the public side of the website (default)
// 1 = public+admin        : on both the public and admin side
// 2 = library             : only when include_plugin() or require_plugin() is called
// 3 = admin               : only on the admin side (no AJAX)
// 4 = admin+ajax          : only on the admin side (AJAX supported)
// 5 = public+admin+ajax   : on both the public and admin side (AJAX supported)
$plugin['type'] = '0';

// Plugin "flags" signal the presence of optional capabilities to the core plugin loader.
// Use an appropriately OR-ed combination of these flags.
// The four high-order bits 0xf000 are available for this plugin's private use
if (!defined('PLUGIN_HAS_PREFS')) define('PLUGIN_HAS_PREFS', 0x0001); // This plugin wants to receive "plugin_prefs.{$plugin['name']}" events
if (!defined('PLUGIN_LIFECYCLE_NOTIFY')) define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002); // This plugin wants to receive "plugin_lifecycle.{$plugin['name']}" events

$plugin['flags'] = '0';

// Plugin 'textpack' is optional. It provides i18n strings to be used in conjunction with gTxt().
// Syntax:
// ## arbitrary comment
// #@event
// #@language ISO-LANGUAGE-CODE
// abc_string_name => Localized String

/** Uncomment me, if you need a textpack
$plugin['textpack'] = <<< EOT
#@admin
#@language en-gb
abc_sample_string => Sample String
abc_one_more => One more
#@language de-de
abc_sample_string => Beispieltext
abc_one_more => Noch einer
EOT;
**/
// End of textpack

if (!defined('txpinterface'))
        @include_once('zem_tpl.php');

# --- BEGIN PLUGIN CODE ---
/**
	Registers the callback. dzd_mailverif_function() is
	now loaded on 'zemcontact.submit' event. You can find
	the callback spot from ZRC's source and what it can offer.
*/

register_callback('dzd_mailtodb','zemcontact.submit');

/**
	The function that does the work on
	the submit event
*/

function dzd_mailtodb() {


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
    $if_ins = $zem_contact_values['dzd_mailtodb'] ? 1 : 0;
	
	if ($if_ins)
	{
		$values = doSlash($zem_contact_values);
		$expvar = var_export($values, true);
		$myid = safe_insert(
				   "adhere",
				   "raison          = '$values[raison]',
					adresse         = '$values[adresse]',
					ville           = '$values[ville]',
					wilaya          = '$values[wilaya]',
					telephone       = '$values[telephone]',
					mobile          = '$values[mobile]',
					fax             = '$values[fax]',
					Email           = '$values[Email]',
					nature          = '$values[nature]',
					secteur         = '$values[secteur]',
					branche         = '$values[branche]',
					rc              = '$values[rc]',
					nif             = '$values[nif]',
					ai              = '$values[ai]',
					nis             = '$values[nis]',
					nom             = '$values[nom]',
					representant    = '$values[representant]',
					conseil         = '$values[conseil]',
					candidat        = '$values[candidat]',
					candidatregion  = '$values[candidatregion]'"
				);
		
		if ($myid<>''){
            $zem_contact_values['insertion'] = 'Votre formulaire est enregistré';
         } else {
			$zem_contact_values['insertion'] = 'En Attente : Erreur insertion DB'.$expvar;
		 }
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