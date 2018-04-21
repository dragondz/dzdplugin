<?php

// This is a PLUGIN TEMPLATE for Textpattern CMS.

// Copy this file to a new name like abc_myplugin.php.  Edit the code, then
// run this file at the command line to produce a plugin for distribution:
// $ php abc_myplugin.php > abc_myplugin-0.1.txt

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Plugin names should start with a three letter prefix which is
// unique and reserved for each plugin author ("abc" is just an example).
// Uncomment and edit this line to override:
$plugin['name'] = 'dzd_counter_view';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
# $plugin['allow_html_help'] = 1;

$plugin['version'] = '0.3';
$plugin['author'] = 'Messaoudi Rabah (Dragondz)';
$plugin['author_uri'] = 'http://info.ix-dz.com/';
$plugin['description'] = 'This plugin make a counter view of an article using a custom field for counting';

// Plugin load order:
// The default value of 5 would fit most plugins, while for instance comment
// spam evaluators or URL redirectors would probably want to run earlier
// (1...4) to prepare the environment for everything else that follows.
// Values 6...9 should be considered for plugins which would work late.
// This order is user-overrideable.
$plugin['order'] = '5';

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

if (!defined('txpinterface'))
        @include_once('zem_tpl.php');

if (0) {
?>
# --- BEGIN PLUGIN HELP ---

h1. dzd_counter_view

This plugin is used to count the number of articles views
To work it need to name a custom field : countxx
and put a numeric value in it ( 0 or what you want )
then you put : ==<txp:dzd_counter_view />== in an article form
To display a counter just output the customfield value like that : ==<txp:custom_field name="countxx" />==

-category = "categry1,category2,..."
bq. set category to restrict the count only for the desired categories, if not set all categories are available.

-section = "section1,section2,..."
bq. set section to restrict the count only for the desired sections, if not set all sections are available.

-force = "1" or "0" (default 0)
bq. this argument force to put 0 in custom_field "countxx" if it is not numeric or blank, default 0 not enforce the value.

That's all

# --- END PLUGIN HELP ---
<?php
}

function dzd_counter_view($atts) {

	global $prefs, $id, $thisarticle;

	extract(lAtts(array(
                        'custom_field' =>'countxx',
			'category' => '',
			'section' => '',
			'force' => 0,
		),$atts));

	$key = array_search($custom_field, $prefs);
	if ($category<>'' ){
		$cat1 = (in_list($thisarticle['category1'], $category) ? 1 : 0) || (in_list($thisarticle['category2'], $category) ? 1 : 0);
		} else {
		$cat1 = 1;
	}
	if ($section<>'' ){
		$sec1 = in_list($thisarticle['section'], $section) ? 1 : 0;
	} else {
		$sec1 = 1;
	}

	if (($key<>"") && $cat1 && $sec1) {

	        $key = str_replace("_set", "", $key);

		if (is_numeric($thisarticle[$custom_field]))
		{
			$cal = $thisarticle[$custom_field]+1;
			$updated = safe_update("textpattern",$key."='".doSlash($cal)."'","ID='".doSlash($id)."'");
		} elseif ($force) {
                        $cal = '0';
			$updated = safe_update("textpattern",$key."='".doSlash($cal)."'","ID='".doSlash($id)."'");
		}
	}

}

# --- END PLUGIN CODE ---

?>
