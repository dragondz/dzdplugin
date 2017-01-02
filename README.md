# dzdplugin
My textpattern pluggins

## dzd_mailverif

### Français:

Ce pluggin est une extension du plugin zem_contact_reborn, c’est un bon exemple de la manière d’étendre les capacité de ce dernier.

Le but de ce pluggin est de stocker les données du formulaire soumis grace à zem_contact_reborn dans la base de donnée (table “textpattern”) sous forme d’article standard de Textpattern sans interrompre l’envoi du mail.

Le pluggin nécessite : zem_contact_reborn, la partie validation nécessite les pluggins adi_gps et smd_query

### English:

This plugin is an example of plugin extension to zem_contact_reborn.

His purpose is to store data provided by zem_contact_reborn into DB table “textpattern” as regular articles, and then continue sending mail.

This plugin require : zem_contact_reborn, the validation process require pluggins adi_gps and smd_query

## dzd_multicat_creator

### Français:

Ce pluggin permet de créer un ensenble de catégories en une seule opération.

#### Installation

Necessite textpattern 4.3.0 ou plus

Activer le plugin

Il ajoute un bouton "Multiple category creator" en bas de la fenêtre categorie.

#### Fonctionnement

Cliquez sur "Multiple category creator", ce qui déroule un champs textarea, copier/coller la structure de catégorie à créer depuis un tableur (OpenOfiice Calc ou Excel ou tout autre tableur), et cliquez sur "Send".

La structure est interprété par le plugin et affiché en dessous du textarea, ce qui vous permet de vérifier que tout est correctement interprété.

Puis cliquez sur "Create categories" pour créer la structure dans Textpattern.

NB. Pour voir apparaitre la structure il est nécessaire de recharger la page "Organiser" de Textpattern.

#### Comment créer la structure de catégories

Dans un tableur la structure doit avoir la forme suivante :

name, title, parent, type(article, image, link, file)

Ex:

table(dzdtable){border-collapse: collapse;}.
|mycat|MyTitle|myparent|article|
|cate|Cétacé|mycat|article|

NB. Vous n'avez pas besoins de mettre des entêtes pour la structure.

### English:

This plugin help creating multiple categories at once.

#### Installation

Require textpattern 4.3.0 or above

Activate the plugin

it adds title "Multiple category creator" at the bottom of category tab.

#### How it works

Click on "Multiple category creator", that will show an area, copy/past the structure from spreadsheet (OpenOffice calc or Excel or other), and click "Send".

The structure is then interpreted and shown as a tabular data, that let you check if all is correct.

Then click on "Create categories" to create the categories.

NB. To be able to see the new categories you must reload the categoy tab.

#### Data structure

The data to make on spreadsheet must be:

name, title, parent, type(article, image, link, file)

Ex:

table(dzdtable){border-collapse: collapse;}.
|mycat|MyTitle|myparent|article|
|cate|Cétacé|mycat|article|

NB. You dont need a header in your structure
