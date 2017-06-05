<?php
/*
Plugin Name: VINDI Konduto connect
Plugin URI: http://www.webcodelab.click/anti-fraude
Description: A maneira mais facil de implementar o Konduto Anti Fraude para quem usa a VINDI
Version: 1.0
Author: ThiegoCarvalho
Author URI: http://www.freela.mobi
License: GPLv2
*/

/** Add link page in sidebar */
add_action('admin_menu', 'konduto_vindi_menu');

function konduto_vindi_menu() {
add_menu_page('VINDI Konduto Connect', 'VINDI Konduto Connect', 'administrator', __FILE__, 'vindi_konduto_connect_settings_page' , plugins_url('/icon.png', __FILE__) );
	add_action( 'admin_init', 'register_vindi_konduto_connect_settings' );
}

/** Registers the configuration group */
function register_vindi_konduto_connect_settings() {

	register_setting( 'vindi-konduto-connect-group', 'konduto_publick_key' );
}
/** Configuration page */
function vindi_konduto_connect_settings_page() {

	/** Creates form */
?>

<div class="wrap">
<h1>VINDI Konduto Connect</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'vindi-konduto-connect-group' ); ?>
    <?php do_settings_sections( 'vindi-konduto-connect-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Konduto Public Key</th>
        <td><input type="text" size="15" name="konduto_publick_key" value="<?php echo esc_attr( get_option('konduto_publick_key') ); ?>" /></td>
        </tr>

    </table>

    <?php submit_button(); ?>

</form>
</div>
<?php }

/** Konduto Script */

function konductohero() {
?>
<?php $current_user = wp_get_current_user();
$usermail= $current_user->user_email; //Get the email from the wordpress user logged in
?>

<script type="text/javascript">
	var __kdt = __kdt || [];
__kdt.push({"public_key": "<?php echo esc_attr( get_option('konduto_publick_key') ); ?>"}); // value of the public key field of Konduto
	(function() {
		var kdt = document.createElement('script');
		kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
		kdt.async = true;    kdt.src = 'https://i.k-analytix.com/k.js';
		var s = document.getElementsByTagName('body')[0];
		s.parentNode.insertBefore(kdt, s);
  	 })();
</script>

<script type="text/javascript">
					var customerID = "<?php echo($usermail); ?>";// Sets the customer ID
		(function() {
	var period = 300;
	var limit = 20 * 1e3;
	var nTry = 0;
	var intervalID = setInterval(function() { // Send Loop
		var clear = limit/period <= ++nTry;
		if ((typeof(Konduto) !== "undefined") && (typeof(Konduto.setCustomerID) !== "undefined")  && (typeof(customerID) !== "undefined")) {
          window.Konduto.setCustomerID(customerID); // Validates if the user is logged in and send ID to Konduto
		clear = true;
	}
	if (clear) {
 clearInterval(intervalID);
}
}, period);
 })(customerID);
  </script>
<?php
}
add_action('wp_footer', 'konductohero'); // insert script in wordpress footer
// That's all, folks
?>
