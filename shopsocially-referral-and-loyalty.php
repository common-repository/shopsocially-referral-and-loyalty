<?php
/* 
Plugin Name: ShopSocially Referral and Loyalty
Plugin URI: https://shopsocially.com/
Description: ShopSocially platform to enable referral marketing, customer loyalty, visual commerce, social gamification, social proof and social login on their sites.
Author: Shop Socially
Version: 1.0
Author URI: https://shopsocially.com/
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if(!defined('ABSPATH'))exit; //Exit if accessed directly

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'shop_socially_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'shop_socially_remove' );

/*
 * Add new page in wordpress when plugin is activated 
 */
function shop_socially_install() {
	//install loyalty get user page
	install_loyalty_getuser_page();
	
	//install loyalty dashboard page
	install_loyalty_dashboard_page();
}


function install_loyalty_getuser_page(){
	$page_title_getuser = 'get-loyalty-users';
	$page_name_getuser = 'get-loyalty-users';

	// the menu entry...
	delete_option("shopsocially_getuser_page_title");
	add_option("shopsocially_getuser_page_title", $page_title_getuser, '', 'yes');
	// the slug...
	delete_option("shopsocially_getuser_page_name");
	add_option("shopsocially_getuser_page_name", $page_name_getuser, '', 'yes');
	// the id...
	delete_option("shopsocially_getuser_page_id");
	add_option("shopsocially_getuser_page_id", '0', '', 'yes');

	$page = get_page_by_title( $page_title_getuser );

	if ( ! $page ) {
		// Create post object
		$_p = array();
		$_p['post_title'] = $page_title_getuser;
		$_p['post_content'] = "This page is automaticaly created by ShopSocially Plugin for Loyalty section.Please don't remove this page";
		$_p['post_status'] = 'publish';
		$_p['post_type'] = 'page';
		$_p['comment_status'] = 'closed';
		$_p['ping_status'] = 'closed';
		$_p['post_category'] = array(1); // the default 'Uncatrgorised'
		// Insert the post into the database
		$page_id_getuser = wp_insert_post( $_p );
	}
    else {
		// the plugin may have been previously active and the page may just be trashed...
		$page_id_getuser = $page->ID;
		//make sure the page is not trashed...
		$page->post_status = 'publish';
		$page_id_getuser = wp_update_post( $page );
	}
	delete_option( 'shopsocially_getuser_page_id' );
	add_option( 'shopsocially_getuser_page_id', $page_id_getuser );
	
}


function install_loyalty_dashboard_page(){
	$page_title_loyaltydash = 'loyalty-dashboard';
	$page_name_loyaltydash = 'loyalty-dashboard';

	// the menu entry...
	delete_option("shopsocially_loyaltydash_page_title");
	add_option("shopsocially_loyaltydash_page_title", $page_title_loyaltydash, '', 'yes');
	// the slug...
	delete_option("shopsocially_loyaltydash_page_name");
	add_option("shopsocially_loyaltydash_page_name", $page_name_loyaltydash, '', 'yes');
	// the id...
	delete_option("shopsocially_loyaltydash_page_id");
	add_option("shopsocially_loyaltydash_page_id", '0', '', 'yes');

	$page = get_page_by_title( $page_title_loyaltydash );

	if ( ! $page ) {
		// Create post object
		$_k = array();
		$_k['post_title'] = $page_title_loyaltydash;
		$_k['post_content'] = '<div id="ss_loyalty_div" style="padding:10px;"></div>';
		$_k['post_status'] = 'publish';
		$_k['post_type'] = 'page';
		$_k['comment_status'] = 'closed';
		$_k['ping_status'] = 'closed';
		$_k['post_category'] = array(1); // the default 'Uncatrgorised'
		// Insert the post into the database
		$page_id_loyaltydash = wp_insert_post( $_k );
	}
    else {
		// the plugin may have been previously active and the page may just be trashed...
		$page_id_loyaltydash = $page->ID;
		//make sure the page is not trashed...
		$page->post_status = 'publish';
		$page_id_loyaltydash = wp_update_post( $page );
	}
	delete_option( 'shopsocially_loyaltydash_page_id' );
	add_option( 'shopsocially_loyaltydash_page_id', $page_id_loyaltydash );
	
}

/*
 * Remove  page when plugin  deactivated 
 */
function shop_socially_remove() {
	// Remove Loyalty Get User Page
	$page_title = get_option( "shopsocially_getuser_page_title" );
	$page_name = get_option( "shopsocially_getuser_page_name" );
	//  the id of our page...
	$page_id = get_option( 'shopsocially_getuser_page_id' );
	if( $page_id ) {
		wp_delete_post( $page_id,true ); // this will trash, not delete
	}

	delete_option("shopsocially_getuser_page_title");
	delete_option("shopsocially_getuser_page_name");
	delete_option("shopsocially_getuser_page_id");
	
	// Remove Loyalty Dashboard Page
	$page_title_loyaltydash = get_option( "shopsocially_loyaltydash_page_title" );
	$page_name_loyaltydash = get_option( "shopsocially_loyaltydash_page_name" );
	//  the id of our page...
	$page_id_loyaltydash = get_option( 'shopsocially_loyaltydash_page_id' );
	if( $page_id_loyaltydash ) {
		wp_delete_post( $page_id_loyaltydash,true ); // this will trash, not delete
	}

	delete_option("shopsocially_loyaltydash_page_title");
	delete_option("shopsocially_loyaltydash_page_name");
	delete_option("shopsocially_loyaltydash_page_id");
	
	// Remove config variable
	delete_option("shop_socially_config_data");	
}


/* Add menu in admin area */
function shopsocially_admin_menu() {
	add_menu_page( 'ShopSocially', 'ShopSocially', 'manage_options', __FILE__, 'shopsocially_admin_page', 'dashicons-tickets', 81  );
}
add_action( 'admin_menu', 'shopsocially_admin_menu' );

/*
 * Add Option page in admin area 
 * Create Configuration setting page
 */
function shopsocially_admin_page() {
?>
	<div class="wrap">
	<h2><?php echo _e( 'SHOP SOCIALLY SETTINGS' ) ?></h2>
	</div>
	
<?php
	if(isset($_POST['add_shop_socially_data'])) {
		
		$config_value = array(
					'shopsocially_partner_id'			=> isset($_POST['shopsocially_partner_id']) ? $_POST['shopsocially_partner_id'] : '' ,
					'shopsocially_api_key'				=> isset($_POST['shopsocially_api_key']) ? $_POST['shopsocially_api_key'] : '' ,
					'enable_shopsocially'				=> isset($_POST['enable_shopsocially']) ? $_POST['enable_shopsocially'] : '',
					'enable_advanced_integration'		=> isset($_POST['enable_advanced_integration']) ? $_POST['enable_advanced_integration']:'' ,
					'enable_shopsocially_social_login'	=> isset($_POST['enable_shopsocially_social_login']) ? $_POST['enable_shopsocially_social_login'] : '' ,
					'enable_shopsocially_qa'			=> isset($_POST['enable_shopsocially_qa']) ? $_POST['enable_shopsocially_qa'] : '',
					'enable_shopsocially_loyality'		=> isset($_POST['enable_shopsocially_loyality']) ? $_POST['enable_shopsocially_loyality'] : '',
					);
			update_option('shop_socially_config_data',$config_value);
	}
	
	$info = get_option('shop_socially_config_data');
	
	$shopsocially_partner_id			= isset($info['shopsocially_partner_id']) ? $info['shopsocially_partner_id'] : '';
	$shopsocially_api_key				= isset($info['shopsocially_api_key']) ? $info['shopsocially_api_key'] : '';
	$enable_shopsocially				= isset($info['enable_shopsocially']) ? $info['enable_shopsocially'] : '';
	$enable_advanced_integration		= isset($info['enable_advanced_integration']) ? $info['enable_advanced_integration'] : '';
	$enable_shopsocially_social_login	= isset($info['enable_shopsocially_social_login']) ? $info['enable_shopsocially_social_login'] : '';
	$enable_shopsocially_qa				= isset($info['enable_shopsocially_qa']) ? $info['enable_shopsocially_qa'] : '';
	$enable_shopsocially_loyality		= isset($info['enable_shopsocially_loyality']) ? $info['enable_shopsocially_loyality'] : '';
	
?>
	<form  id="createuser"  method="post">
		<table class="form-table">
			<tbody>
			<tr class="form-field form-required">
				<th scope="row"><label><?php echo _e( 'ShopSocially Partner Id' ) ?> </label></th>
				<td><input type="text" autocorrect="off" aria-required="true" value="<?php echo $shopsocially_partner_id ?>" name="shopsocially_partner_id">
				<p style="font-size:12px;">To enable this extension, you will need a ShopSocially Partner ID. </p>
				<p style="font-size:12px;">Chick <a target="_blank" href="https://shopsocially.com">here</a> to register and get your ShopSocially Partner ID . </p>
				</td>
			</tr>
			<tr class="form-field form-required">
				<th scope="row"><label><?php echo _e( 'ShopSocially API key' ) ?> </label></th>
				<td><input type="text" value="<?php echo $shopsocially_api_key ?>" name="shopsocially_api_key"></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label><?php echo _e( 'Enable ShopSocially' ) ?> </label></th>
				<td><input type="checkbox" style="width:1em"  value="1" name="enable_shopsocially"<?php checked('1', $enable_shopsocially); ?>></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label><?php echo _e( 'Enable Advanced Integration' ) ?> </label></th>
				<td><input type="checkbox" style="width:1em" value="1" name="enable_advanced_integration"<?php checked('1', $enable_advanced_integration); ?>></td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label><?php echo _e( 'Enable Social Login' ) ?> </label></th>
				<td><input type="checkbox" style="width:1em" value="1" name="enable_shopsocially_social_login"<?php checked('1', $enable_shopsocially_social_login); ?>>
				<p style="font-size:12px;">Please put the below code at the place where you want the social login buttons to appear :
				<pre><strong>&lt;div id="ssmi_social_login_div" style="margin: 0 auto;">&lt;/div&gt;</strong></pre></p>
				</td>
			<tr class="form-field">
				<th scope="row"><label><?php echo _e( 'Enable Customer Q & A ' ) ?> </label></th>
				<td><input type="checkbox" style="width:1em" value="1" name="enable_shopsocially_qa"<?php checked('1', $enable_shopsocially_qa); ?>>
				<p style="font-size:12px;">Please put the below code at the place where you want the Customer Q and A app to appear :
				<pre><strong>&lt;div id="ss_qna_div">&lt;/div&gt;</strong></pre></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row"><label><?php echo _e( 'Enable Loyalty' ) ?> </label></th>
				<td><input type="checkbox" style="width:1em" value="1" name="enable_shopsocially_loyality"<?php checked('1', $enable_shopsocially_loyality); ?>></td>
			</tr>
			
			<tr class="form-field">
				<th scope="row"><label><?php echo _e( 'Loyalty Authentication URL' ) ?> </label></th>
				<td>
				<p style="color:blue;text-decoration:underline;font-size:12px;"><?php echo site_url('wp-login.php?loyalty-dashboard=true');?></p>
				<p style="font-size:12px;">This link hosts the authentication script for logging a Shopping cartuser into ShopSocially's Loyalty Program. </p>
				<p style="font-size:12px;">Copy this link and paste it in the Loyalty Authentication URL field in ShopSocially Merchant Center Console>>Loyalty>>Dashboard Options</p>
				</td>
			</tr>
			
			<tr class="form-field">
				<th scope="row"><label><?php echo _e( 'Loyalty User Dashboard URL' ) ?> </label></th>
				<td>
				<p style="color:blue;text-decoration:underline;font-size:12px;"><?php echo site_url('/loyalty-dashboard/');?></p>
				<p style="font-size:12px;">This link hosts the Loyalty User Dashboard URL where the user can view and redeem points.  </p>
				<p style="font-size:12px;">Copy this link and paste it in the User Dashboard URL field in ShopSocially Merchant Center Console>>Loyalty>>Dashboard Options</p>
				</td>
			</tr>
			
			<tr class="form-field">
				<th scope="row"><label><?php echo _e( 'Loyalty User Endpoint URL' ) ?> </label></th>
				<td>
				<p style="color:blue;text-decoration:underline;font-size:12px;"><?php echo site_url('/get-loyalty-users/');?></p>
				<p style="font-size:12px;">ShopSocially will hit this endpoint to get loyalty user information.</p>
				<p style="font-size:12px;">Copy this link and paste it in the User Endpoint field in ShopSocially Merchant Center Console>>Loyalty>>Dashboard Options</p>
				</td>
			</tr>
			
			</tbody>
		</table>
		<p class="submit"><input type="submit" value="Save Configuration" name="add_shop_socially_data" class="button button-primary" ></p>
	</form>
<?php 
}

/*
 * Insert Javascript in all pages  
 * pass partner id got from shop socially site
 */
function enable_shopsocially_js() {
	$info = get_option('shop_socially_config_data');	
	$enable_shopsocially		= isset($info['enable_shopsocially']) ? $info['enable_shopsocially'] : '';
	$shopsocially_partner_id	= isset($info['shopsocially_partner_id']) ? $info['shopsocially_partner_id'] : '';	
	
	if(!empty($enable_shopsocially) && !empty($shopsocially_partner_id)) {
		$output = "<script type='text/javascript'>
				SSConfig = {
					partner_id: '".$shopsocially_partner_id."' /*REQUIRED: Also known as Account ID */
				};
			_ssq = (typeof _ssq === 'undefined')?[]:_ssq;
			_ssq.push(['init', SSConfig]);
			(function() {
				var ss = document.createElement('script');ss.src = '//shopsocially.com/js/all.js';
				ss.type = 'text/javascript';ss.async = 'true';
				var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(ss, s);
			})();
		</script>";
		echo $output;
	}
	
}
// Add hook for front-end <head></head>
add_action('wp_head', 'enable_shopsocially_js');

/*
 * Advanced integration 
 * Add javascript code in order page 
 * Pass variables in javascript
 */
function shopsocially_advanced_integration() {
	
	// Get value from url to get current Order Id
	$meta_value = $_GET['key'];	
	$order_id  = get_current_order($meta_value);
	
	$info = get_option('shop_socially_config_data');
	$enable_advanced_integration		= isset($info['enable_advanced_integration']) ? $info['enable_advanced_integration'] : '';
	$shopsocially_partner_id	= isset($info['shopsocially_partner_id']) ? $info['shopsocially_partner_id'] : '';
	
	// Check if option is enabled from configuration & Order id is not empty  
	if($enable_advanced_integration && $order_id ) {
	$order = new WC_Order($order_id);
	
	
	$currency = $order->get_order_currency();
	$revenue = $order->get_total();
	$user_email = get_post_meta($order_id,'_billing_email', true );
	$user_first_name = get_post_meta($order_id,'_billing_first_name', true );
	$user_last_name = get_post_meta($order_id,'_billing_last_name', true );
	$zipcode = get_post_meta($order_id,'_billing_postcode', true );
	$coupon_code = is_coupon_used($order_id);
	$is_already_purchased = wc_get_customer_orders();
	$is_new_customer = ($is_already_purchased == TRUE) ? 'NO' : 'YES';
	
	?>
	<div id="ssFrame"></div> 
	<script language="javascript" type="text/javascript">_ssq = [];</script>
	<script language="javascript" type="text/javascript" src="https://shopsocially.com/js/all.js"></script>
	<script language="javascript" type="text/javascript">
	ss_mi.init({
		user_email: '<?php echo $user_email ; ?>', /* OPTIONAL: Email Address of Shopper */
		user_first_name: '<?php echo $user_first_name ; ?>', /* OPTIONAL: First Name of Shopper */
		user_last_name: '<?php echo $user_last_name ; ?>', /* OPTIONAL: Last Name of Shopper */
		zipcode: '<?php echo $zipcode ; ?>', /* OPTIONAL: Zipcode of Shopper */
		order_id: '<?php echo $order_id ; ?>', /* OPTIONAL: Internal Order ID */
		new_customer: '<?php echo $is_new_customer ; ?>', /* RECOMMENDED: Indicates if buyer is a new customer.YES, NO, NA */
		revenue : '<?php echo $revenue ; ?>', /* OPTIONAL: Total Order Value */
		transaction_type: 'SALE', /* RECOMMENDED: Indicates type of conversion.SALE, FORMFILL */
		coupon_code:'<?php echo $coupon_code ?>', /* RECOMMENDED: Coupon code used for that conversion */
		is_oc_page: true, /* REQUIRED: Boolean. Indicates that this script is only on the thank you page */
		partner_id: '<?php echo $shopsocially_partner_id ; ?>' /* REQUIRED: Also known as Account ID */
	});
	
	ss_mi.add_products([ /* Note: You can add an unlimited number of products to this array */  
		<?php  $count=0; foreach ($order->get_items() as $key => $product) { 
			 if($count>0) { 	?>
		   , 
		<?php }
				$products = new WC_product($product['product_id']);
				$image_id = $products->get_image_id();
				$product_price = $products->price;
				$url = wp_get_attachment_thumb_url( $image_id );  
		?>
		{
		prod_currency:'<?php echo $currency ; ?>', /* RECOMMENDED: 3 letter currency code, USD, GBP etc. */
		prod_price:'<?php echo $product_price ; ?>', /* RECOMMENDED: Product Price */
		quantity:'<?php echo $product['qty'] ; ?>', /* OPTIONAL: Number of items ordered for this product */
		prod_id:'<?php echo $product['product_id'] ; ?>', /* OPTIONAL: Product ID, must be unique */
		prod_page_url:'<?php echo get_site_url().'/'.$product['name'] ?>', /* REQUIRED */
		prod_img_url:'<?php echo $url ; ?>', /* REQUIRED: Ideal width 250px */
		prod_title:'<?php echo $product['name'] ; ?>' /* REQUIRED */
		}
		
		<?php $count++; } ?>
	]);
	ss_mi.load_ssFrame();
	ss_mi.award_loyalty_points('made_a_purchase', <?php echo $revenue ?>); /* Total Order Value e.g.39.50 */
	</script>
		<?php	 } 	
	}
	// Add hook for thankyou page
add_action( 'woocommerce_thankyou', 'shopsocially_advanced_integration' );


/*
 * Get current order id  
 * @param meta_value 
 * @return order id
 */
function get_current_order($meta_value) {
global $wpdb;

if($meta_value)
$result = $wpdb->get_row( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_value ='".$meta_value."' ",ARRAY_A ) );
	if ( ! $result ) {
		return FALSE;
	}
	else {
		return $result->post_id; 
	}	
}

/*
 * Check no of orders to check wheather new user or existing 
 * @return True False
 */
function wc_get_customer_orders() {    
    // Get all customer orders
	$customer_orders = get_posts( array(
	'numberposts' => -1,
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types(),
	'post_status' => array_keys( wc_get_order_statuses() ),
	) );

	$customer = wp_get_current_user();	
	$user_ID = get_current_user_id();
	if($user_ID==0){
		return FALSE;
		}

	if ( count( $customer_orders ) > 1 ) {
		return TRUE;
	}
	else {
		return FALSE;
	}
}

/*
 * Check wheather coupon code is usesd in order or not  
 * @param order id
 * @return coupon code
 */
function is_coupon_used($order_id) {
global $wpdb;

if($order_id)
$result = $wpdb->get_row( $wpdb->prepare( "SELECT order_item_name FROM ".$wpdb->prefix."woocommerce_order_items WHERE order_item_type='coupon' 
AND order_id ='".$order_id."' ",ARRAY_A ) );
	if ( ! $result ) {
		return FALSE;
	}
	else {
		return $result->order_item_name; 
	}	
}

/*
 * Add JS in product detail page 
 */
function share_product_info() {
	$info = get_option('shop_socially_config_data');
	$enable_shopsocially_qa	= isset($info['enable_shopsocially_qa']) ? $info['enable_shopsocially_qa'] : '';
	
	if($enable_shopsocially_qa) {
		global $post;
		
		$id = $post->ID;
		$title = $post->post_title;
		$page_url = str_replace('#038;','',$post->guid);
		$post_type = $post->post_type;
		
		//$page_url = get_site_url().'/?post_type='.$post_type.'&p='.$id;
		$prod_info = new WC_product($id);
		$image_id = $prod_info->get_image_id();
		$image_url = wp_get_attachment_thumb_url($image_id);		
		?> 				
		<script language="javascript" type="text/javascript">
			window.get_qna_product_info = function(){
				return { product_id :'<?php echo $id ?>',
				page_url :'<?php echo $page_url ?>',
				title : '<?php echo $title ?>',
				image_url : '<?php echo $image_url  ?>'
				};
			} 
		</script>
<?php 
	}
}
add_action( 'woocommerce_after_single_product_summary', 'share_product_info');

/*
 * Add user unique token id for every login  
 * generate unique user_login_token_id
 */
function add_login_token($login){
	$user = get_user_by('login',$login);
	$user_ID = $user->ID;
	$generate_user_login_token=md5(uniqid(rand(), true));
	update_user_meta($user_ID,'user_login_token_id',$generate_user_login_token);	
	}
add_action('wp_login', 'add_login_token', 99);

/*
 * Add Loyalty JS in every page if option is enabled 
 */
function add_loyality_js(){
	$info = get_option('shop_socially_config_data');
	$enable_shopsocially_loyality	= isset($info['enable_shopsocially_loyality']) ? $info['enable_shopsocially_loyality'] : '';
	
	if($enable_shopsocially_loyality) {
		if(!(current_user_can('manage_options')) && (is_user_logged_in()==TRUE) ) {
		$is_user_logged_in = 'true';
		}
		else
		{
		$is_user_logged_in = 'false';
		}
		
		$token_id = get_current_user_session_id();		
		?>
		
	<script language="javascript" type="text/javascript"> 
		var user_info = {};
		var is_loyalty ;
		user_info.access_token = '<?php echo $token_id ?>';
		user_logged_into_website = <?php echo $is_user_logged_in ?>;		
		is_loyalty = document.referrer.search('loyalty-dashboard=true') > -1

		/*define the function*/
		authenticate_ss_loyalty_user = function () {
			if(typeof ss_mi === 'undefined'){
				return setTimeout(authenticate_ss_loyalty_user, 1000);
			}else if (!ss_mi.is_loyalty_user_logged_in()){
				ss_mi.authenticate_loyalty_user(user_info);
			}
		}
		
		/*Call the function if user is logged into Woocommerce site */
		if(user_logged_into_website) {			
			authenticate_ss_loyalty_user();
		}
		/* loyalty authentication success handler*/
		window.ssmi_authenticate_loyalty_user_success_handler = function(){
		if (is_loyalty){
			window.location = ss_mi.authenticate_loyalty_user_resp.loyalty_conf.user_dashboard_url;
		}else{
			//do nothing
		}
		};
		
		//Logout JS
		check_and_logout_ss_user = function () {
			var is_user_logged_in_to_merchant_site = <?php echo $is_user_logged_in ?>;
				if(!is_user_logged_in_to_merchant_site) {
					if(typeof ss_mi === 'undefined'){
						return setTimeout(check_and_logout_ss_user, 1000);
					} else {
					if(!ss_mi.partner_id) {
						return setTimeout(check_and_logout_ss_user,1000);
					} else {
					var isloyaltyUserLoggedIn = ss_mi.is_loyalty_user_logged_in();
					 if(isloyaltyUserLoggedIn == true){
							ss_mi.logout_loyalty_user();
						}
					}
				}
			}
		};
			check_and_logout_ss_user();
			window.ssmi_logout_loyalty_user_success_handler = function() {
			//blank function
			}
		
			window.ssmi_logout_loyalty_user_failure_handler = function() {
			//blank function
			}
	</script>
<?php	}
	}
// Add hook for front-end <head></head>
add_action('wp_head', 'add_loyality_js');

/*
 * Get user's unique login token id  
 * @return user_login_token_id
 */
function get_current_user_session_id(){
	$user_ID = get_current_user_id();
	$token_id = get_user_meta($user_ID,'user_login_token_id', true );
		if($token_id) {
			return $token_id;
		}
	}
	
/*
 * Add Social Login JS in header 
 * Create new user if not registered
 * Auto Login after user creation
 */
function social_login_integration() {
	$info = get_option('shop_socially_config_data');
	$enable_shopsocially_social_login	= isset($info['enable_shopsocially_social_login']) ? $info['enable_shopsocially_social_login'] : '';
	
	if(($enable_shopsocially_social_login) && (is_user_logged_in()==FALSE)) { ?>		
		<script language="javascript" type="text/javascript">	
		// Below is the successful login handling function. This function is called ONLY IF the social login was successful
			var ssmi_social_login_success = function(user_info){
				//console.log(user_info);return false;
				var access_token=user_info.access_token;
				
				var ajaxurl = '<?php echo admin_url("admin-ajax.php") ?>';		
				jQuery(document).ready(function($) {
					var data = {
					action: 'my_action',
					whatever: access_token
					};
					// post data and get response
					$.post(ajaxurl, data, function(response) {
						window.location.replace('<?php echo site_url('/my-account'); ?>');
						//alert('Got this from the server: ' + response);
					});
				});	
			};
		// Below is the login failure handling function. This function is called ONLY IF the social login failed
		var ssmi_social_login_failure = function(failure_info){

		};
		</script>
 <?php 
	}
} 
 // Add hook for front-end <head></head>
add_action('wp_head', 'social_login_integration');

// The function that handles the AJAX request
function my_action_callback($access_token) {	
	$access_token = $_POST['whatever'];
	$info = get_option('shop_socially_config_data');
	$shopsocially_partner_id	= isset($info['shopsocially_partner_id']) ? $info['shopsocially_partner_id'] : '';
	$shopsocially_api_key		= isset($info['shopsocially_api_key']) ? $info['shopsocially_api_key'] : '';
	
	if($access_token){
		$curlhandle = curl_init();

		$headers = array();
		$headers[] = 'partner-id:'.$shopsocially_partner_id;
		$headers[] = 'api-key:'.$shopsocially_api_key;

		curl_setopt($curlhandle, CURLOPT_URL, 'https://shopsocially.com/ss_user/validate_access_token?access_token='.$access_token) ;
		curl_setopt($curlhandle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curlhandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlhandle, CURLOPT_SSL_VERIFYPEER, 0);
		$contents = curl_exec ($curlhandle);
		curl_close ($curlhandle);
		
		$user_data = json_decode($contents);
			if($user_data){
				update_user_data($user_data);
			}
		}
	} 
add_action( 'wp_ajax_my_action', 'my_action_callback' );
add_action( 'wp_ajax_nopriv_my_action', 'my_action_callback' );

/* Update user info in database */
function update_user_data($user_info){	
	$email = (!empty($user_info->data->email))? $user_info->data->email :'' ;
	if(empty($email)){
		return false;
		}
	$is_user_exists = get_user_by( 'email', $email );
	if (!$is_user_exists ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "users";
		$login_name = $email;
		$current_date_time = date("Y-m-d h:i:s");
		$data = array(
		'user_login' =>$email,
		'user_email' =>$user_info->data->email ,
		'user_registered' =>$current_date_time ,
		'display_name' =>$user_info->data->first_name ,
		'user_nicename' =>$user_info->data->first_name ,
		);
		$wpdb->insert( $table_name, $data );
		$new_user_id =  $wpdb->insert_id;		
		
		$table_name_meta = $wpdb->prefix . "usermeta";		
		$meta_data_level = array(
		'user_id' =>$new_user_id ,
		'meta_key' =>'wp_user_level' ,
		'meta_value' =>'0' 
		);
		$wpdb->insert( $table_name_meta, $meta_data_level );
		
		$meta_data_cap = array(
		'user_id' =>$new_user_id ,
		'meta_key' =>'wp_capabilities' ,
		'meta_value' =>'a:1:{s:10:"subscriber";b:1;}'
		);
		$wpdb->insert( $table_name_meta, $meta_data_cap );
		
		$meta_data_first_name = array(
		'user_id' =>$new_user_id ,
		'meta_key' =>'first_name' ,
		'meta_value' =>$user_info->data->first_name
		);
		$wpdb->insert( $table_name_meta, $meta_data_first_name );
		
		$meta_data_last_name = array(
		'user_id' =>$new_user_id ,
		'meta_key' =>'last_name' ,
		'meta_value' =>$user_info->data->last_name
		);
		$wpdb->insert( $table_name_meta, $meta_data_last_name );
		
		$meta_data_gender = array(
		'user_id' =>$new_user_id ,
		'meta_key' =>'user_fb_gender' ,
		'meta_value' =>$user_info->data->facebook->gender
		);
		$wpdb->insert( $table_name_meta, $meta_data_gender );
		
		$meta_data_date_of_birth = array(
		'user_id' =>$new_user_id ,
		'meta_key' =>'user_fb_date_of_birth' ,
		'meta_value' =>$user_info->data->facebook->date_of_birth
		);
		$wpdb->insert( $table_name_meta, $meta_data_date_of_birth );
		
		$meta_data_image_url = array(
		'user_id' =>$new_user_id ,
		'meta_key' =>'user_fb_image_url' ,
		'meta_value' =>$user_info->data->facebook->image_url
		);
		$wpdb->insert( $table_name_meta, $meta_data_image_url );
		
		//Auto Login after new user creation
		auto_login($login_name);
	}
	else
	{
		$login_name = $is_user_exists->user_login;
		auto_login($login_name);
	} 
	
	}
	
/* Auto Login user If Social Login */
function auto_login($login_name='') {
    if (!is_user_logged_in() && ($login_name)) {
        //determine WordPress user account to impersonate
        $user_login = $login_name;

       //get user's ID
        $user = get_userdatabylogin($user_login);
        $user_id = $user->ID;

        //login
        wp_set_current_user($user_id, $user_login);
        wp_set_auth_cookie($user_id);
        do_action('wp_login', $user_login);
    }
} 
add_action('init', 'auto_login');

/* get user information based on token id */
function return_user_info() {
	global $post; 
			 
	if (!empty($_GET['access_token']) && ($post->post_type=='page') && $post->post_name=='get-loyalty-users') { 
	$access_token = $_GET['access_token'];
	$partner_id = $_GET['merchant_id'];
	$info = get_option('shop_socially_config_data');
	$shopsocially_partner_id		= isset($info['shopsocially_partner_id']) ? $info['shopsocially_partner_id'] : '';
	$enable_shopsocially_loyality	= isset($info['enable_shopsocially_loyality']) ? $info['enable_shopsocially_loyality'] : '';
	
		if(($shopsocially_partner_id==$partner_id) && $enable_shopsocially_loyality){
			$user_data = get_users(array('meta_key' => 'user_login_token_id', 'meta_value' => $access_token));
			if($user_data){
				$user_info = array();
				$user_info['first_name']	= $user_data[0]->data->display_name;
				$user_info['last_name']		= '';
				$user_info['email']			= $user_data[0]->data->user_email;
				$user_info['uid']			= $user_data[0]->data->ID; 
				$user_info['phone_number']	= ''; 
					
				$info = json_encode($user_info);
				echo  $info;
				exit;
			}
				echo json_encode(array('message'=>'No user data found'));				
				exit;
		}
			echo json_encode(array('message'=>'Authantication failed check merchant id'));				
			exit;
	}
}
add_action('wp', 'return_user_info');

?>
