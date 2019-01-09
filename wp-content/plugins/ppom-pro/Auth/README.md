# Auth
N-Media Plugin Authentication Handling Script

* Checkout this repo inside plugin root
* Paste following script into plugin main file after includes etc

<pre>
// Authencation checking
if( ! class_exists('NM_Auth') ) {
	$_auth_class = dirname(__FILE__).'/Auth/auth.php';
	if( file_exists($_auth_class))
		include_once($_auth_class);
	else
		die('Reen, Reen, BUMP! not found '.$_auth_class);
}

/**
 * Plugin API Validation
 * *** DO NOT REMOVE These Lines
 * */
define('PPOM_PLUGIN_PATH', "nm-woocommerce-personalized-product/ppom.php");
define('PPOM_REDIRECT_URL', admin_url( 'admin.php?page=ppom' ));
define('PPOM_PLUGIN_ID', 2235);
NM_AUTH(PPOM_PLUGIN_PATH, PPOM_REDIRECT_URL, PPOM_PLUGIN_ID);
</pre>

* Change Class name
* Wrap plugin menu/access point under following condition

<pre>
if( NM_AUTH(PPOM_PLUGIN_PATH, PPOM_REDIRECT_URL, PPOM_PLUGIN_ID) -> api_key_found() ) {
	THE MENU PAGE OR SOMETHING
}
</pre>