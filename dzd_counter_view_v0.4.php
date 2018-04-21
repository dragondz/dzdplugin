<?php

// This is a PLUGIN TEMPLATE for Textpattern CMS.

// Copy this file to a new name like abc_myplugin.php.  Edit the code, then
// run this file at the command line to produce a plugin for distribution:
// $ php abc_myplugin.php > abc_myplugin-0.1.txt

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Plugin names should start with a three letter prefix which is
// unique and reserved for each plugin author ('abc' is just an example).
// Uncomment and edit this line to override:
$plugin['name'] = 'dzd_counter_view';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
# $plugin['allow_html_help'] = 0;

$plugin['version'] = '0.4';
$plugin['author'] = 'Messaoudi Rabah (Dragondz)';
$plugin['author_uri'] = 'http://info.ix-dz.com/';
$plugin['description'] = 'Count article pageviews using a custom field';

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
if (!defined('PLUGIN_HAS_PREFS')) {
    define('PLUGIN_HAS_PREFS', 0x0001);
} // This plugin wants to receive "plugin_prefs.{$plugin['name']}" events
if (!defined('PLUGIN_LIFECYCLE_NOTIFY')) {
    define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002);
} // This plugin wants to receive "plugin_lifecycle.{$plugin['name']}" events

$plugin['flags'] = '0';

if (!defined('txpinterface')) {
    @include_once('zem_tpl.php');
}

if (0) {
    ?>
# --- BEGIN PLUGIN HELP ---

h1. dzd_counter_view

This plugin can be used to count article pageviews using a custom field.

You can count pageviews for:

* individual articles you explicitly specify
* all articles
* articles in specific sections or categories.

h3. Setting up

# Install the plugin and activate it.
# Set up a custom field to use as your counter. The default custom field name is @countxx@ but you may use any custom field name you like.
# Add the tag @dzd_counter_view@ in your individual article form, or in the "if_individual_article":https://docs.textpattern.io/tags/if_individual_article section of a form or txp:article(_custom) container. Use the @custom_field@ attribute to specify your own custom field name and the @section@ and/or @category@ attributes to count only articles in specific sections or categories (see the tag reference)
# If you wish to add a counter to specific articles, edit the respective articles in the and enter value @0@ in the corresponding custom field. The counter will only advance on those articles. If you wish to add the counter to all articles, use the attribute @force="1"@ (see the tag reference).

The counter will advance by 1 every time the url for the page is accessed. You can follow the counter by visiting the article edit page. If you want to display the counter value on your homepage, use @<txp:custom_field name="countxx" />@ (or your own custom field name).

h2. Tag reference

h3. Tag: @<txp:dzd_counter_view>@

Logs each pageview to a custom field.

; *custom_field*
: Name of the custom field to use for the counter.
: Example: @custom_field="pageviews"@ uses the custom field "pageviews"
: Default: @countxx@
; *category*
: Restrict counter to articles in the specified category/categories.
: Example: @category="apples,pears"@
: Default: _empty_ (=no restriction)
; *section*
: Restrict counter to articles in the specified section(s).
: Example: @section="fruit,vegetables"@
: Default: _empty_ (=no restriction)
; *force*
: Force all articles to use a counter. Set this if you want your counter on all articles. You no longer need to manually set the custom field to zero in each article. The section/category filters still apply.
: Example: @force="1"@
: Default: @0@
; *reset*
: Reset the article counter to a specified number. Only whole numbers are permitted.
: Example: @reset="0"@ resets the counter to zero, @reset="10"@ resets the counter to ten.
: Default: _empty_
; *operator*
: Optionally subtract instead of advance the counter.
: Example: @operator="-"@ or @operator="subtract"@ sets the counter to subtract one.
: Default: @add@


h2. Examples

_*Reminder:* Before you start, set up a custom field you wish to use for the counter. Then set the value of that custom field to @0@ for all the articles that should be counted._


h3. Example: Adding dzd_counter_view to Textpattern's @default@ article form.

Add the @dzd_counter_view@ tag to the @if_individual_article@ section of the form. The following is part of the top of the @default@ form:

bc.. <txp:if_individual_article>
    <h1 itemprop="headline">
        <txp:title />
    </h1>
    <txp:dzd_counter_view />
<txp:else />
    <h1 itemprop="headline">
        <a href="<txp:permlink />" itemprop="url">
            <txp:title />
        </a>
    </h1>
</txp:if_individual_article>

p. This example assumes you are using a custom field called @countxx@.


h3. Example: Using your own custom field name

The default custom field name is @countxx@. If you wish to use another custom field name, for example "pageviews", use:

bc. <txp:dzd_counter_view custom_field="pageviews" />


h3. Example: Displaying the counter

bc.. <txp:if_custom_field name="countxx" value=""><txp:else />
    <p class="counter">This article has been viewed <txp:custom_field name="countxx" /> times.</p>
</txp:if_custom_field>

p. If using a different custom field name, replace @countxx@ with your own custom field name.


h3. Example: Count all articles in the "tips" section

bc.. <txp:if_individual_article>
    <h1><txp:title /></h1>
    <txp:dzd_counter_view section="tips" force="1" />
</txp:if_individual_article>

p. Using the attribute @force="1"@ means you do not need to manually add "0" to your custom field to start the counter.

If you wish to count just the textpattern tips articles (in section "tips" and category "textpattern"), you could use:

bc. <txp:dzd_counter_view section="tips" category="textpattern" force="1" />


h3. Example: Get the top ten accessed articles in the "tips" section

bc.. <txp:article_custom section="tips" sort="custom_2+0 desc" limit="10" wraptag="ul" break="li" label="Top ten tips" labeltag="h3">
    <txp:title />: <txp:custom_field name="countxx" /> pageviews
</txp:article_custom>

p. Note that for the @sort@ attribute, you need to use *custom_X* instead of the custom field name, where *X* is the custom field number. The addition of @+0@ makes txp:article_custom sort numerically (i.e. 1-9 comes before 10) and using @desc@ sorts from largest to smallest.

If you have less than 10 counted articles, you will need to stop empty article counters showing, e.g.:

bc.. <txp:article_custom section="tips" sort="custom_2+0 desc" limit="10" wraptag="ul" break="li" label="Top ten tips" labeltag="h3">
    <txp:if_custom_field name="countxx" value=""><txp:else />
        <txp:title />: <txp:custom_field name="countxx" /> pageviews
    </txp:if_custom_field>
</txp:article_custom>


h3. Example: Show the first ten visitors of the week a freebie

This is an example of using the @reset@ and @operator@ attributes.

bc.. <!-- Put day of the week in a variable: 0 = sunday … 6 = saturday -->
<txp:variable name="weekday"><txp:php>echo date('w');</txp:php></txp:variable>
<!-- It's Monday (1): reset counter to 10 if countdown is in 'starter state' -->
<txp:if_custom_field name="countxx" value="99">
    <txp:if_variable name="weekday" value="1">
        <txp:dzd_counter_view reset="10" />
    </txp:if_variable>
</txp:if_custom_field>
<!-- It's end of the week (Sunday: 0), set countdown to 'starter state' (99) -->
<txp:if_variable name="weekday" value="0">
    <txp:dzd_counter_view reset="99" />
</txp:if_variable>
<!-- Show the freebie message if the counter (custom field 'countxx') is 1-10 -->
<txp:if_custom_field name="countxx" match="pattern" value="^([1-9]|10)$">
    <txp:dzd_counter_view operator="subtract" />
    <p>Hello, Early Bird! Here's your freebie.</p>
</txp:if_custom_field>

p. In this example the counter is reset to '10' on Monday and the message is displayed only for counter values of 1-10 (regex pattern: @^([1-9]|10)$@). Each time the message is displayed, the counter decreases by 1 (@operator="subtract"@) until 0 is reached and the message no longer displays.

To ensure the counter resets to 10 only once on a Monday, the reset can only happen when the counter is 'primed to start'. For this we use a counter value that is well out of range (e.g. 99). At the end of the week, (e.g. on Sunday: weekday=0), the starter state is reset to '99' in readiness for Monday.

You could use the same code with @echo date('j');@ to make an announcement on a particular day of each month, or with @echo date('G');@ for a Happy Hour offer. See the php "date()":php.net/manual/function.date.php function for more details.


h2. Help & Changelog

Help is always available from the friendly people on the "textpattern forum":https://forum.textpattern.io/viewtopic.php?pid=126594. French instructions are available on "my homepage":http://info.ix-dz.com/plugin/8/dzdcounterview-02.

h3. Changelog

h4. v0.4

* Tag registered with the txp tag registry.
* Added optional @reset@ attribute.
* Added optional @operator@ attribute to optionally subtract per pageview.
* More informative help.

h4. v0.3

* Added optional @custom_field@ attribute to specif own custom fields.

h4. v0.2

* Added optional @section@ and @category@ attributes to restrict counting to certain sections or categories.
* Added optional @force@ attribute to apply counter to all articles (not just those with the counter custom field set to 0)

h4. v0.1

* Initial version

# --- END PLUGIN HELP ---
<?php
}

// Register public tag with the tag registry
if (class_exists('\Textpattern\Tag\Registry')) {
    Txp::get('\Textpattern\Tag\Registry')
        ->register('dzd_counter_view');
}


function dzd_counter_view($atts)
{
    global $prefs, $id, $thisarticle;

    extract(lAtts(array(
            'custom_field' => 'countxx',
            'category' => '',
            'section' => '',
            'force' => 0,
            'reset' => '',
            'operator' => 'add'
        ), $atts));

    // Get custom field
    $key = array_search($custom_field, $prefs);

    // Only count articles in a specified category
    if ($category<>'') {
        $cat1 = (in_list($thisarticle['category1'], $category) ? 1 : 0) || (in_list($thisarticle['category2'], $category) ? 1 : 0);
    } else {
        $cat1 = 1;
    }

    // Only count articles in a specified section
    if ($section<>'') {
        $sec1 = in_list($thisarticle['section'], $section) ? 1 : 0;
    } else {
        $sec1 = 1;
    }

    // If custom field exists (and in category/section if specified)
    if (($key<>'') && $cat1 && $sec1) {
        $key = str_replace("_set", "", $key);

        if (is_numeric($thisarticle[$custom_field])) {
            if ($reset<>'' && is_numeric($reset)) {
                // Reset counter to an integer
                $cal = $reset;
            } else {
                if (in_array($operator, array('-','subtract'))) {
                    // Subtract counter by 1
                    $cal = $thisarticle[$custom_field]-1;
                } else {
                    // Advance counter by 1
                    $cal = $thisarticle[$custom_field]+1;
                }
            }
        } elseif ($force) {
            // Populate empty custom_field with 0
            $cal = '0';
        }

        // Update custom field value
        $updated = safe_update("textpattern", $key."='".doSlash($cal)."'", "ID='".$id."'");
    }
}

# --- END PLUGIN CODE ---

?>
