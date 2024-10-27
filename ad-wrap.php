<?php
/*
Plugin Name: Ad Wrap
Plugin URI: http://www.geekyramblings.net/plugins/ad-wrap
Description: Simple plugin to wrap text in context ad service tags to identify important context.
Version: 0.6
Tested up to: 3.1.0
Author: David Gibbs
Author URI: http://www.geekyramblings.net
*/

function wrap_ads ($text) {

	$google_adsense = get_option('ad_wrap_google_adsense');
	$amazon = get_option('ad_wrap_amazon');
	$kontera = get_option('ad_wrap_kontera');
	$infolinks = get_option('ad_wrap_infolinks');

	$prefix = "";
	if ($kontera) {
		$prefix .="\n<div class=\"KonaBody\">";
	}
	if ($google_adsense) {
		  $prefix .= "\n<!-- google_ad_section_start -->";
	}
	if ($infolinks) {
		  $prefix .= "\n<!--INFOLINKS_ON-->";
	}

	$sufix = "";
	if ($infolinks) {
		$sufix .= "\n<!--INFOLINKS_OFF-->";
	}
	if ($google_adsense) {
		$sufix .= "\n<!-- google_ad_section_end -->";
	}
	if ($kontera) {
		 $sufix .= "\n</div> <!-- KonaBody -->";
	}

	return $prefix."\n<p>".$text.$sufix."\n";
}


function ad_wrap_options_menu() {

	?>
	<div class="wrap">
	<h2>Ad Wrap</h2>
	<?php _e('Wrap the post content and comments, individually, in the appropriate markers for various context ad services.') ?><p/>
	<h3><?php _e('Context ad servcies currently supported...') ?></h3>
	<ul>
		<li><a href="http://www.google.com/adsense">Google AdSense</a></li>
		<li><a href="http://www.kontera.com/ads-for-site/become-a-kontera-publisher">Kontera ContextLink</a></li>
		<li><a href="http://www.infolinks.com/signup.html">infolinks</a></li>
	</ul>
	<?php _e('Contact') ?> <a href="mailto:plugin-support@midrange.com">plugin-support@midrange.com</a> <?php _e('to request additional context ad services.  Please provide details on how the ad service identifies relevent content (usually with html comments or div sections.') ?>
	<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>

<table class="form-table">
 <tr>
        <th scope="row" valign="top">Google AdSense</th>
        <td>
        <input id="ad_wrap_google_adsense" type="checkbox" name="ad_wrap_google_adsense" <?php echo get_option('ad_wrap_google_adsense')?'checked=checked':''; ?> />
                <label for="ad_wrap_google_adsense"><?php _e('Check this box to wrap text in') ?> <code>&lt!-- google_ad_section_start --&gt;</code> <?php _e('and') ?> <code>&lt;!-- google_ad_section_end --&gt;</code></label>
        </td>
 </tr>
 <tr>
        <th scope="row" valign="top">Kontera ContentLink</th>
        <td>
        <input id="ad_wrap_kontera" type="checkbox" name="ad_wrap_kontera" <?php echo get_option('ad_wrap_kontera')?'checked=checked':''; ?> />
                <label for="ad_wrap_kontera"><?php _e('Check this box to wrap text in') ?> <code>&lt;div class="KonaBody"&gt;</code> <?php _e('and') ?> <code>&lt;/div&gt;</code></label>
        </td>
 </tr>
 <tr>
        <th scope="row" valign="top">infolinks</th>
        <td>
        <input id="ad_wrap_infolinks" type="checkbox" name="ad_wrap_infolinks" <?php echo get_option('ad_wrap_infolinks')?'checked=checked':''; ?> />
                <label for="ad_wrap_kontera"><?php _e('Check this box to wrap text in') ?> <code>&lt;!--INFOLINKS_ON--&gt;</code> <?php _e('and') ?> <code>&lt;!--INFOLINKS_OFF--&gt;</code></label>
        </td>
 </tr>
</table>
<p/>
<b><?php _e('NOTE') ?>:</b> <?php _e('This plug-in does <u>NOT</u> add the code to invoke the actual ad service.  It just provides markers to the ad service to identify the important content.  Consider using ') ?><a href="http://www.geekyramblings.net/plugins/general-headers/">General Headers</a> <?php _e('to add arbitrary code to the headers and/or footers of your content.') ?>

        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
        </p>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="ad_wrap_google_adsense,ad_wrap_kontera,ad_wrap_infolinks"/>
	</form>
	</div>
	<?php 

}

function ad_wrap_menu() {
    add_options_page('Ad Wrap', 'Ad Wrap', 8, __FILE__, 'ad_wrap_options_menu');
}

function ad_wrap_activate() {
        // Let's add some options
	// add_option('ad_wrap_label', 'Technorati Tags');

}

function ad_wrap_deactivate() {
        // Clean up the options
	delete_option('ad_wrap_google_adsense');
	delete_option('ad_wrap_infolinks');
	delete_option('ad_wrap_kontera');
}

add_option('ad_wrap_google_adsense', true);
add_option('ad_wrap_kontera', true);
add_option('ad_wrap_infolinks', true);
add_option('ad_wrap_version', '0.3');
add_action('admin_menu', 'ad_wrap_menu');

add_filter ('the_content', 'wrap_ads');
add_filter ('comment_text', 'wrap_ads');

// drop mediatext & amazon if they are set, as the services are no
// longer available.
if (get_option('ad_wrap_mediatext')) {
	delete_option('ad_wrap_mediatext');
}

if (get_option('ad_wrap_amazon')) {
	delete_option('ad_wrap_amazon');
}

?>
