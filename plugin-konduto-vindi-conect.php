<?php
/*
Plugin Name: VINDI Konduto connect
Plugin URI: http://www.freela.mobi/antifraude
Description: A maneira mais facil de implementar o Konduto Anti Fraude para quem usa a VINDI
Version: 1.1
Author: ThiegoCarvalho
Author URI: http://www.freela.mobi
License: GPLv2
*/

/** Add link page in sidebar */
add_action('admin_menu', 'konduto_vindi_menu');

function konduto_vindi_menu() {
add_menu_page('Antifraude', 'Antifraude', 'administrator', __FILE__, 'vindi_konduto_connect_settings_page');
	add_action( 'admin_init', 'register_vindi_konduto_connect_settings' );
}

/** Registers the configuration group */
function register_vindi_konduto_connect_settings() {
	register_setting( 'vindi-konduto-connect-group', 'konduto_publick_key', 'konduto_publick_key_validate' );
}
/** Configuration page */
function vindi_konduto_connect_settings_page() {

	/** Creates form */
?>

<div class="wrap">

<h1>VINDI Konduto Connect</h1>
<?php settings_errors(); ?>
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

function plugin_add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=vindi-konduto-connect%2Fplugin-konduto-vindi-conect.php">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );



/** Konduto Script */
function konductohero() {
?>
<?php $current_user = wp_get_current_user();
$usermail= $current_user->user_email;
?>
<script type="text/javascript">
	var __kdt = __kdt || [];
__kdt.push({"public_key": "<?php echo esc_attr( get_option('konduto_publick_key') ); ?>"});
	(function() {
		var kdt = document.createElement('script');
		kdt.id = 'kdtjs'; kdt.type = 'text/javascript';
		kdt.async = true;    kdt.src = 'https://i.k-analytix.com/k.js';
		var s = document.getElementsByTagName('body')[0];
		s.parentNode.insertBefore(kdt, s);
  	 })();
</script>
<?php
 if (empty($usermail)){
?>
<script type="text/javascript">
var visitorID;
(function() {
	var period = 300;
	var limit = 20 * 1e3;
	var nTry = 0;
	var intervalID = setInterval(function() {
	var clear = limit/period <= ++nTry;
	if ((typeof(Konduto) !== "undefined") &&
            (typeof(Konduto.getVisitorID) !== "undefined")) {
		visitorID = window.Konduto.getVisitorID();
		clear = true;
	}
	if (clear) {
 clearInterval(intervalID);
}
}, period);
})(visitorID);
</script>

<?php
 }
 else{
?>
<script type="text/javascript">
	var customerID = "<?php echo($usermail); ?>";
	(function() {
	var period = 300;
	var limit = 20 * 1e3;
	var nTry = 0;
	var intervalID = setInterval(function() {
		var clear = limit/period <= ++nTry;
		if ((typeof(Konduto) !== "undefined") &&
	 	    (typeof(Konduto.setCustomerID) !== "undefined")) {
		window.Konduto.setCustomerID(customerID);
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
}
add_action('wp_footer', 'konductohero'); // insert script in wordpress footer

//Navigate Tags

function konduto_meta_tags() {
	if ( class_exists( 'WooCommerce' ) ) {
		//Página de Esqueci minha senha
	if (is_wc_endpoint_url( 'lost-password' )) {
		echo '<meta name="kdt:page" content="password-reset">';
	}
	//Processo de checkout
	if (is_checkout()) {
		echo '<meta name="kdt:page" content="checkout">';
	}
	//Detalhe de produto
		if (is_product()) {
		$nome = get_the_title();
		echo '<meta name="kdt:page" content="product">';
		echo '<meta name="kdt:product" content="name='.$nome.'">';
	}
}

}
add_action('wp_head', 'konduto_meta_tags');

?>
