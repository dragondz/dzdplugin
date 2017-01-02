<?php

// This is a PLUGIN TEMPLATE.

// Copy this file to a new name like abc_myplugin.php.  Edit the code, then
// run this file at the command line to produce a plugin for distribution:
// $ php abc_myplugin.php > abc_myplugin-0.1.txt

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Plugin names should start with a three letter prefix which is
// unique and reserved for each plugin author ("abc" is just an example).
// Uncomment and edit this line to override:
$plugin['name'] = 'dzd_multicat_creator';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
# $plugin['allow_html_help'] = 1;

$plugin['version'] = '0.2';
$plugin['author'] = 'Messaoudi Rabah (Dragondz)';
$plugin['author_uri'] = 'http://info.ix-dz.com';
$plugin['description'] = 'Add multiples categories at once';

// Plugin load order:
// The default value of 5 would fit most plugins, while for instance comment
// spam evaluators or URL redirectors would probably want to run earlier
// (1...4) to prepare the environment for everything else that follows.
// Values 6...9 should be considered for plugins which would work late.
// This order is user-overrideable.
$plugin['order'] = '5';

// Plugin 'type' defines where the plugin is loaded
// 0 = public       : only on the public side of the website (default)
// 1 = public+admin : on both the public and admin side
// 2 = library      : only when include_plugin() or require_plugin() is called
// 3 = admin        : only on the admin side
$plugin['type'] = '3';

// Plugin "flags" signal the presence of optional capabilities to the core plugin loader.
// Use an appropriately OR-ed combination of these flags.
// The four high-order bits 0xf000 are available for this plugin's private use
if (!defined('PLUGIN_HAS_PREFS')) define('PLUGIN_HAS_PREFS', 0x0001); // This plugin wants to receive "plugin_prefs.{$plugin['name']}" events
if (!defined('PLUGIN_LIFECYCLE_NOTIFY')) define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002); // This plugin wants to receive "plugin_lifecycle.{$plugin['name']}" events

$plugin['flags'] = '0';

if (!defined('txpinterface'))
        @include_once('zem_tpl.php');

# --- BEGIN PLUGIN CODE ---
//<?php


register_callback('dzd_multicat_creator', 'category', '');

function dzd_multicat_creator($event, $step) {
   global $sitename, $prefs, $thisarticle, $txp_user, $txpcfg;

  $myarea = '';
	$confirm = '';

    if (ps('dzdsend'))
    {
			$myarea = ps('cat_content');
			$confirm = '<input type="submit" name="dzdconfirm" value="Create categories" />';
			$up_form = <<<uform1
<script>
$(document).ready(function() {
$('div.txp-layout').after('<form method="post" action="index.php?event=category" id="dzd_form" class="toggle" style="text-align:center;" name="dzd_form" ><input type="hidden" value="category" name="event" /><textarea name="cat_content" rows="10" cols="100">$myarea</textarea><br /><input type="submit" name="dzdconfirm" value="Create categories" /></form>');
$('div.txp-layout').after('<h3 class="plain lever" style="padding-top: 10px;text-align:center;"><a href="#dzd_form" id="dzdlink">Multiple category creator</a></h3>');
});
</script>
<h3 class="plain lever" style="padding-top: 10px;text-align:center;"><a href="#dzd_form">Multiple category creator</a></h3>
<form method="post" action="index.php?event=category" id="dzd_form" class="toggle" style="text-align:center;" name="dzd_form" >
<input type="hidden" value="category" name="event" />
<textarea name="cat_content" rows="10" cols="100">$myarea</textarea><br />
<input type="submit" name="dzdconfirm" value="Create categories" />
</form>
uform1;

    } else {

    $up_form = <<<uform1
<script>
$(document).ready(function() {
$('div.txp-layout').after('<form method="post" action="index.php?event=category" id="dzd_form" class="toggle" style="display:none;text-align:center;" name="dzd_form" ><input type="hidden" value="category" name="event" /><textarea name="cat_content" rows="10" cols="100"></textarea><br /><input type="submit" name="dzdsend" value="Send" /></form>');
$('div.txp-layout').after('<h3 class="plain lever" style="padding-top: 10px;text-align:center;"><a href="#dzd_form" id="dzdlink">Multiple category creator</a></h3>');
$('#dzdlink').click(function() {
$('form#dzd_form').toggle();
});
});
</script>
uform1;
	}
	
	if (ps('dzdconfirm'))
    {
		$myarea = ps('cat_content');
	}
	

  if ($myarea <>'')
  {
		$up_form .='<table style="text-align: center;width:50%;" ><tr><th>Name</th><th>Title</th><th>Parent</th><th>Type</th></tr>';
			$data_array = explode("\n", $myarea);
			foreach($data_array as $key=>$value)
			{
				 //  becareful to check the value for  empty line 
				 $value=trim($value);
			 
			 $up_form .='<tr>';
				 if  (!empty($value))
				 {
							$array1 = explode("\t", $data_array[$key]);
							foreach($array1 as $key1=>$value1)
							{
									$value1=trim($value1);
									$myarray[$key][$key1] = $value1;
					$up_form .= '<td>'.$value1.'</td>';
							}
				if (ps('dzdconfirm'))
				{
					$mes = my_category_create($myarray[$key][3], $myarray[$key][0], $myarray[$key][2], $myarray[$key][1]);
					$up_form .='<td>'.$mes.'</td>';
				}
				 }
			 $up_form .='</tr>';
			}
		$up_form .= '</table>';

	}
	
	echo $up_form;

}

function my_category_create($event,$title1,$papa='root',$ti)
	{
		global $txpcfg;

		$name = sanitizeForUrl($title1);
		
		if (!$name)
		{
			$message = gTxt($event.'_category_invalid', array('{name}' => $name));

			return $message;
		}

		$exists = safe_field('name', 'txp_category', "name = '".doSlash($name)."' and type = '".doSlash($event)."'");

		if ($exists)
		{
			$message = gTxt($event.'_category_already_exists', array('{name}' => $name));

			return $message;
		}
		

		$q = safe_insert('txp_category', "name = '".doSlash($name)."', title = '".doSlash($ti)."', type = '".doSlash($event)."', parent ='" .doSlash($papa)."'");

		if ($q)
		{
		rebuild_tree('root', 1, $event);

		$message = 'OK';
		return $message;
		}
	} 
# --- END PLUGIN CODE ---
if (0) {
?>
<!--
# --- BEGIN PLUGIN CSS ---
<style type="text/css">
.dzdtable td
{
border: 1px solid #666 ! important;
}
</style>
# --- END PLUGIN CSS ---
-->
<!--
# --- BEGIN PLUGIN HELP ---
h1. dzd_multicat_creator

This plugin help creating multiple categories at once.

h2. Installation

Require textpattern 4.3.0 or above

Activate the plugin

it adds title "Multiple category creator" at the bottom of category tab.

h2. How it works

Click on "Multiple category creator", that will show an area, copy/past the structure from spreadsheet (OpenOffice calc or Excel or other), and click "Send".

The structure is then interpreted and shown as a tabular data, that let you check if all is correct.

Then click on "Create categories" to create the categories.

NB. To be able to see the new categories you must reload the categoy tab.

h2. Data structure

The data to make on spreadsheet must be:

name, title, parent, type(article, image, link, file)

Ex:

table(dzdtable){border-collapse: collapse;}.
|mycat|MyTitle|myparent|article|
|cate|Cétacé|mycat|article|

NB. You dont need a header in your structure

h2. History

0.1 Initial release

h1. dzd_multicat_creator (Français)

Ce plugin permet de créer plusieurs catégories en une seule fois.

h2. Installation

nécessite Textpattern 4.3.0 or plus.

Installer le plugin et l'activer.

Il ajoute un titre "Multiple category creator" en bas de la page "Contenu/Organiser"

h2. Comment ça fonctionne

Cliquez sur "Multiple category creator", ce qui déroule un champs textarea, copier/coller la structure de catégorie à créer depuis un tableur (OpenOfiice Calc ou Excel ou tout autre tableur), et cliquez sur "Send".

La structure est interprété par le plugin et affiché en dessous du textarea, ce qui vous permet de vérifier que tout est correctement interprété.

Puis cliquez sur "Create categories" pour créer la structure dans Textpattern.

NB. Pour voir apparaitre la structure il est nécessaire de recharger la page "Organiser" de Textpattern.

h2. Comment créer la structure de catégories

Dans un tableur la structure doit avoir la forme suivante :

name, title, parent, type(article, image, link, file)

Ex:

table(dzdtable){border-collapse: collapse;}.
|mycat|MyTitle|myparent|article|
|cate|Cétacé|mycat|article|

NB. Vous n'avez pas besoins de mettre des entêtes pour la structure.

h2. Historique

0.1 Première mise en ligne
# --- END PLUGIN HELP ---
-->
<?php
}
?>
