<?php
/*
Plugin Name: Ultimate WordPress Cart Start
Plugin URI: http://wpsells.com/blog/plugins/150.html
Description: Продажа товаров любого типа на Вашем блоге. Многоуровневая партнерская программа. Основан на платформе EcommTools.com
Author: Igor Ocheretny
Version: 1.1
Author URI: http://wpsells.com/blog/
*/
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

class UWPCstart {

	var $TinyMCEVersion = 200;
	var $TinyMCEVpluginname = 'UWCartStart';

	function UWPCstart() {

		$this->load_config();
		
		$this->textdomain = $this->load_textdomain();
		
		if ( !$this->required_version() ) return;

		$this->load_styles();
		
		register_activation_hook( __FILE__, array(&$this, 'uwcs_install')); 
		register_deactivation_hook( __FILE__, array(&$this, 'uwcs_uninstall'));

		$this->option_main = get_option('uwcs_main');
		
		wp_register_style('ectAdmincss', UWCS_WP_PLUGIN_CORE_PATH . 'uwcadmin.css');
		wp_enqueue_style('ectAdmincss');

		wp_register_script('ectPopupJS', UWCS_WP_PLUGIN_JS_PATH . 'popups.js', array('jquery'));
		wp_enqueue_script('ectPopupJS');
		add_action('admin_init', array (&$this, 'ect_scripts_init'));
		
		if (function_exists ('add_shortcode')) {
			add_shortcode('uwcart_basket', array(&$this, 'basket_shortcode'));
			add_shortcode('uwcart_price', array(&$this, 'price_shortcode'));
		}
		
		if (function_exists('add_action')) {
			add_action('admin_head', array(&$this, 'insert_ectform_styles'));
			add_action('admin_menu', array(&$this, 'ect_plugin_menu'));
			add_action("admin_init", array(&$this, 'addcosttopost'));
			add_action('save_post', array(&$this, 'save_goods_attributes'));
			add_action('manage_posts_custom_column', array(&$this, 'add_post_column'), 10, 2);
			add_action('manage_pages_custom_column', array(&$this, 'add_post_column'), 10, 2);
			add_action('init', array (&$this, 'addbuttons') );
		}
		
		if (function_exists('add_filter')) {
			add_filter('manage_posts_columns', array(&$this, 'add_posts_columns_header'), 10);
			add_filter('manage_pages_columns', array(&$this, 'add_posts_columns_header'), 10);
			add_filter('tiny_mce_version', array (&$this, 'change_tinymce_version') );
		}
		
		require_once ( dirname (__FILE__) . '/widgets/uwpc_makeorder.php' );
		add_action('widgets_init', create_function('', 'return register_widget("UWCMakeOrder");'));
		
		require_once ( dirname (__FILE__) . '/widgets/uwpc_checkorderstatus.php' );
		add_action('widgets_init', create_function('', 'return register_widget("UWCCheckOrderStatus");'));
			
		require_once ( dirname (__FILE__) . '/widgets/uwpc_checkcoupon.php' );
		add_action('widgets_init', create_function('', 'return register_widget("UWCCheckCoupon");'));
		
		require_once ( dirname (__FILE__) . '/widgets/uwpc_partnerform.php' );
		add_action('widgets_init', create_function('', 'return register_widget("UWCPartnerLogin");'));
		
		require_once ( dirname (__FILE__) . '/widgets/uwpc_promo.php' );
		add_action('widgets_init', create_function('', 'return register_widget("UWCPromo");'));
		
	}
	
	function load_textdomain() {
		
		load_plugin_textdomain('uwcart-start', false, dirname( plugin_basename(__FILE__) ) . '/lang');

	}
	
	function load_config() {
	
		$uwc_plugin_path = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
		$uwc_plugin_path = ereg_replace("/$", "", $uwc_plugin_path);
		
		if(!defined('UWCS_CURRENT_VERSION')) define('UWCS_CURRENT_VERSION', '1.1');

		if(!defined('UWCS_PLUGIN_DIR')) define('UWCS_PLUGIN_DIR', 'uwcart-start');
		if(!defined('UWCS_WP_CONTENT_URL')) define('UWCS_WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
		if(!defined('UWCS_WP_PLUGIN_URL')) define('UWCS_WP_PLUGIN_URL',$uwc_plugin_path);

		if(!defined('UWCS_WP_PLUGIN_CSS_PATH')) define('UWCS_WP_PLUGIN_CSS_PATH',UWCS_WP_PLUGIN_URL.'/css/');
		if(!defined('UWCS_WP_PLUGIN_JS_PATH')) define('UWCS_WP_PLUGIN_JS_PATH',UWCS_WP_PLUGIN_URL.'/js/');
		if(!defined('UWCS_WP_PLUGIN_CORE_PATH')) define('UWCS_WP_PLUGIN_CORE_PATH',UWCS_WP_PLUGIN_URL.'/core/');
		if(!defined('UWCS_WP_PLUGIN_IMG_URL')) define('UWCS_WP_PLUGIN_IMG_URL',UWCS_WP_PLUGIN_URL.'/img/');
		if(!defined('UWCS_WP_PLUGIN_IMG_BUTTONS_URL')) define('UWCS_WP_PLUGIN_IMG_BUTTONS_URL',UWCS_WP_PLUGIN_IMG_URL.'order_buttons/');
		
		if(!defined('UWCS_WP_PLUGIN_IMG_INVIDGET_URL')) define('UWCS_WP_PLUGIN_IMG_INVIDGET_URL',UWCS_WP_PLUGIN_IMG_URL.'inwidget/');
		if(!defined('UWCS_WP_PLUGIN_IMG_INVIDGET_DIR')) define('UWCS_WP_PLUGIN_IMG_INVIDGET_DIR',str_replace('wp-admin','wp-content',getcwd()).'/plugins/uwcart-start/img/inwidget/');

		if(!defined('UWCS_DEFAULT_BASKET_BUTTON')) define('UWCS_DEFAULT_BASKET_BUTTON',UWCS_WP_PLUGIN_IMG_BUTTONS_URL.'buy1_3.png');
		if(!defined('UWCS_DEFAULT_ORDER_BUTTON')) define('UWCS_DEFAULT_ORDER_BUTTON',UWCS_WP_PLUGIN_IMG_BUTTONS_URL.'button_checkout_7.png');
		if(!defined('UWCS_DEFAULT_VIDGET_TEXT1')) define('UWCS_DEFAULT_VIDGET_TEXT1','Для того, чтобы добавить в корзину заказов<br /><strong>&laquo;%ITEM%&raquo;</strong><br />по цене %PRICE%,<br />нажмите кнопку <strong>&laquo;Купить&raquo;</strong>');
		if(!defined('UWCS_DEFAULT_VIDGET_TEXT2')) define('UWCS_DEFAULT_VIDGET_TEXT2','Для оформления и оплаты заказа нажмите кнопку <strong>&laquo;Оформить заказ&raquo;</strong>');
		if(!defined('UWCS_DEF_READMORE')) define('UWCS_DEF_READMORE','Подробнее &raquo;&raquo;');

		if(!defined('UWCS_WP_MIN_VERSION')) define('UWCS_WP_MIN_VERSION', '2.9.2');

	}
	
	/* кнопки шорткодов */
	function addbuttons() {
		if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) return;

		if ( get_user_option('rich_editing') == 'true' ) {
			add_filter("mce_external_plugins", array (&$this, 'add_tinymce_plugin' ), 5);
			add_filter('mce_buttons_3', array (&$this, 'register_button' ), 5);
		}
	}
	
	function register_button($buttons) {
		array_push($buttons, '', 'uwcart_price', 'uwcart_basket');
		return $buttons;
	}
	
	function add_tinymce_plugin($plugin_array) {    
		$plugin_array[$this->TinyMCEVpluginname] = get_option('siteurl') . '/wp-content/plugins/uwcart-start/js/editor_plugin.js';
		return $plugin_array;
	}
	
	function change_tinymce_version($version) {
		$version = $version + $this->TinyMCEVersion;
		return $version;
	}
	
	/**/
	
	// дополнительная колонка в списках постов
	function add_posts_columns_header($defaults) {
	
		$defaults['uwcart-start_cost'] = __('Цена', $this->textdomain).' ('.$this->option_main['ect_currency_type'].')';
        return $defaults;
	
	}
	
	function add_post_column($column_name, $id) {
	
		if ($column_name == 'uwcart-start_cost') {
            $post = get_post($id);
			
			$cost = get_post_meta($post->ID, 'uwc_cost', true);
			if ($cost) echo '<span style="color:green;"><strong>'.$cost.'</strong></span>';
			else echo '---';

        }
	
	}
	
	function addcosttopost() {
		add_meta_box('addcosttopage', __('Атрибуты товара', $this->textdomain), array(&$this, 'addcost_to_page_options'), 'page', 'side', 'high');
		add_meta_box('addcosttopost', __('Атрибуты товара', $this->textdomain), array(&$this, 'addcost_to_page_options'), 'post', 'side', 'high');
	}
	
	function addcost_to_page_options() {
		
		global $post, $wpdb;
		$uwc_cost = get_post_meta($post->ID, 'uwc_cost', true);
		$uwc_good_id = get_post_meta($post->ID, 'uwc_good_id', true);
		echo '<input type="hidden" name="uwc_admin_settings" id="uwc_admin_settings" value="' . 
		wp_create_nonce( plugin_basename(__FILE__) ).'" />';
		
		echo '<div style="padding:12px;line-height:17px;font-size:90%;">';
		echo '<label for="uwc_good_id">'.__("Идентификатор товара", $this->textdomain).':';
		echo '<br /><input type="text" name="uwc_good_id" value="'.$uwc_good_id.'" style="width:100%;margin-bottom:10px;" />';
		echo '</label><br />';

		echo '<label for="uwc_cost">'.__("Цена товара", $this->textdomain).' ('.$this->option_main['ect_currency_type'].'):';
		echo '<br /><input type="text" name="uwc_cost" value="'.$uwc_cost.'" style="width:100%;" /><br /><br />';
		echo '</label>';
		
		echo '</div>';
	
	}
	
	function save_goods_attributes($post_id) {
		global $wpdb;

		if (isset($_POST['post_type'])) $post_type = $_POST['post_type'];
		else $post_type = 'post';

		if ( !isset($_POST['uwc_admin_settings']) || (isset($_POST['uwc_admin_settings']) && !wp_verify_nonce( $_POST['uwc_admin_settings'], plugin_basename(__FILE__) )) ) {
			return $post_id;
		}

		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
			  
		if ( 'page' == $post_type ) {
			if ( !current_user_can( 'edit_page', $post_id ) ) return $post_id;
		} else {
			if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
		}
		
		if ($_POST['uwc_good_id']) {
			$uwc_good_id = $_POST['uwc_good_id'];
			update_post_meta( $post_id, 'uwc_good_id', $uwc_good_id );

			$cost = $_POST['uwc_cost'];
			update_post_meta( $post_id, 'uwc_cost', sprintf("%.2f",$cost) );
		}
		
	}
	
	function required_version() {
		
		global $wp_version;

		$wp_ok  =  version_compare($wp_version, UWCS_WP_MIN_VERSION, '>=');
		
		if ( ($wp_ok == FALSE)) {
			add_action(
				'admin_notices', 
				create_function(
					'', 
					'printf (\'<div id="message" class="error"><p><strong>\' . __(\'Извините, плагин Ultimate WPcart работает только с WordPress версии %s или выше.\', $this->textdomain ) . \'</strong></p></div>\', UWCS_WP_MIN_VERSION );'
				)
			);
			return false;
		}
		
		return true;
		
	}
	
	function load_styles() {

		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$browserIE = false;

		if ( stristr($user_agent, 'MSIE 7.0') ) $browserIE = true; // IE7
		if ( stristr($user_agent, 'MSIE 6.0') ) $browserIE = true; // IE6
		if ( stristr($user_agent, 'MSIE 5.0') ) $browserIE = true; // IE5
		
		if ($browserIE) {
			$styleurl = UWCS_WP_PLUGIN_CSS_PATH . 'uwcart-start_ie.css';
			wp_register_style('uwcStyleSheets', $styleurl);
			wp_enqueue_style( 'uwcStyleSheets', UWCS_WP_PLUGIN_CSS_PATH . 'uwcart-start_ie.css', false, '', 'screen' );
		}
		else {
			$styleurl = UWCS_WP_PLUGIN_CSS_PATH . 'uwcart-start.css';
			wp_register_style('uwcStyleSheets', $styleurl);
			wp_enqueue_style( 'uwcStyleSheets', UWCS_WP_PLUGIN_CSS_PATH . 'uwcart-start.css', false, '', 'screen' );
		}

	}
	
	function uwcs_install() {

		if (false === $this->option_main) {	

			$option_main = array(
				'ect_shop_id'=>'',
				'uwc_refid'=>'',
				'basket_btn_inpost'=>UWCS_DEFAULT_BASKET_BUTTON,
				'basket_btn_inpost_own'=>'',
				'basket_btn'=>UWCS_DEFAULT_BASKET_BUTTON,
				'basket_btn_own'=>'',
				'order_btn'=>UWCS_DEFAULT_ORDER_BUTTON,
				'order_btn_own'=>'',
				'basket_btn_inpost_additional_txt'=>'',
				'basket_btn_inpost_additional_txt_align'=>'left',
				'fast_buy'=>0,
				'ect_currency_type'=>'руб.'
			);
			
			add_option('uwcs_main', $option_main);
			add_option('uwcs_version', UWCS_CURRENT_VERSION);

		}

	}
	
	function uwcs_uninstall() {

		delete_option('uwcs_main');
		delete_option('uwcs_version');
		
	}
	
	function ect_scripts_init() {
        
		wp_register_script('ectAdminJS', UWCS_WP_PLUGIN_JS_PATH . 'admin.js');

	}
	
	function ect_js_sripts() {
        
		wp_enqueue_script('ectAdminJS');

    }
	
	function ect_plugin_menu() {
		
		add_menu_page( 'UWCart-S', 'UWCart-S', 'administrator', __FILE__, array (&$this, 'show_menu'), UWCS_WP_PLUGIN_IMG_URL.'ectlogo_16.png' );
		$page1 = add_submenu_page( __FILE__, __('О плагине', $this->textdomain), __('О плагине', $this->textdomain), 'administrator', __FILE__, array (&$this, 'show_menu'));
		$page2 = add_submenu_page( __FILE__, __('Основные настройки', $this->textdomain), __('Установки', $this->textdomain), 'administrator', 'uwpcmainsettings', array (&$this, 'show_menu'));
		$page3 = add_submenu_page( __FILE__, 'EcommTools', '<a href="javascript:void(0);" onclick="OpenInfoWindow(\'info_block\',270,150,\''.UWCS_WP_PLUGIN_IMG_URL.'\');return false;">EcommTools</a>', 'administrator', 'uwpcecommtools', array (&$this, 'show_menu'));
		$page4 = add_submenu_page( __FILE__, 'InviteMaster', 'InviteMaster', 'administrator', 'uwpcinvitemaster', array (&$this, 'show_menu'));
			
		add_action('admin_print_scripts-' . $page1, array (&$this, 'ect_js_sripts'));
		add_action('admin_print_scripts-' . $page2, array (&$this, 'ect_js_sripts'));
		add_action('admin_print_scripts-' . $page3, array (&$this, 'ect_js_sripts'));
		add_action('admin_print_scripts-' . $page4, array (&$this, 'ect_js_sripts'));

	}
	
	function show_menu() {
		
			switch ($_GET['page']){
				
				case "uwcart-start/uwcart-start.php" :
					require_once (dirname (__FILE__) . '/core/uwcart-start-about.php');
					$ect_page = new UWPCAbout();
					$ect_page->show_about();
				break;
				case "uwpcmainsettings" :
					require_once ( dirname (__FILE__) . '/core/uwcart-start-mainsettings.php' );
					$ect_page = new UWCart();
					$ect_page->mainsettings_form();
				break;
				case "uwpcinvitemaster" :
					require_once (dirname (__FILE__) . '/core/uwcart-start-invitemaster.php');
					$ect_page = new UWPCInviteMaster();
					$ect_page->show_about_im();
				break;
		
		}

	}
	
	function GetTheGoodInfo() {
		global $wpdb;

		$postID = get_the_ID();
		$good_id = get_post_meta($postID, 'uwc_good_id', true);
		$price = get_post_meta($postID, 'uwc_cost', true);
		//$uwc_cost_in_exerpt = get_post_meta($postID, 'uwc_cost_in_exerpt', true);
		
		if ($postID) {
			$row = $wpdb->get_row( $wpdb->prepare("SELECT post_title, guid FROM $wpdb->posts WHERE ID=$postID AND post_status='publish' LIMIT 0,1",$postID), ARRAY_A );

			$good_info['good_id'] = $good_id;
			$good_info['cost'] = $price;
			//$good_info['cost_in_exerpt'] = $uwc_cost_in_exerpt;
			$good_info['name'] = $row['post_title'];
			$good_info['url'] = $row['guid'];
			
			return $good_info;
		}
		else return false;
	}
	
	function price_shortcode ($atts, $content = null) {
		
		$option_main = $this->option_main;
		$currency_type = $option_main['ect_currency_type'];
		$good_info = $this->GetTheGoodInfo();
		$price = $good_info['cost'];
		$price_in_post = '';
		
		if (is_single()) {
			$price_in_post = '<div class="price_inpost"><span class="cword_inpost">'.__('Цена', $this->textdomain).':</span> '.$price.'<span class="ctype_inpost">'.$currency_type.'</span></div>';
		}
		
		return $price_in_post;
		
	}
	
	function basket_shortcode($atts, $content = null) {
	
		$option_main = $this->option_main;
		$basecurrname = $option_main['ect_currency_type'];

		if ($option_main['basket_btn_inpost_own']) $basket_btn_url = $option_main['basket_btn_inpost_own'];
		elseif ($option_main['basket_btn_inpost']) $basket_btn_url = $option_main['basket_btn_inpost'];
		else $basket_btn_url = UWCS_DEFAULT_BASKET_BUTTON;

		$good_info = $this->GetTheGoodInfo();
		$good_id = $good_info['good_id'];
		$cost = $good_info['cost'];
		//$good_type = $good_info['type'];
		
		$price = '<span class="basket_btn_inpost_price">'.$cost.'</span><span class="basket_btn_inpost_curr">'.$basecurrname.'</span>';

		if ($option_main['basket_btn_inpost_additional_txt']) {
			
			$additional_inpostbutton_txt = str_replace("%ITEM%",$good_info['name'],$option_main['basket_btn_inpost_additional_txt']);
			$additional_inpostbutton_txt_replacement2 = $price;
			$additional_inpostbutton_txt = str_replace("%PRICE%",$additional_inpostbutton_txt_replacement2,$additional_inpostbutton_txt);
			
			$basket_in_post = '<div class="basket_box_inpost">';
			
			if ($option_main['basket_btn_inpost_additional_txt_align'] == 'left') {
				
				$basket_in_post .= '<table class="btn_inpost_add_txt" style=";width:100%;"><tr>';
				
				$basket_in_post .= '<td align="left">'.$additional_inpostbutton_txt.'</td>';
				$basket_in_post .= '<td>';
				
				// быстрая покупка
				if ($option_main['fast_buy']) {
					$basket_in_post .= '<form action="http://www.ecommtools.com/cgi-bin/cart.cgi" method="post" target="_blank" style="padding:0px;margin:0px">
					<input type="image" src="'.$basket_btn_url.'" alt="Купить">
					<input type="hidden" name="uid" value="'.$option_main['ect_shop_id'].'">
					<input type="hidden" name="action" value="buy">
					<input type="hidden" name="item" value="'.$good_id.'">
					<input type="hidden" name="pselect" value="">
					<input type="hidden" name="refid" value="">
					</form>';
				}
				// применения корзины заказов
				else {
					$basket_in_post .= '<form action="http://www.ecommtools.com/cgi-bin/cart.cgi" method="post" target="_blank" style="padding:0px;margin:0px">
					<input type="image" src="'.$basket_btn_url.'" alt="Добавить в корзину">
					<input type="hidden" name="uid" value="'.$option_main['ect_shop_id'].'">
					<input type="hidden" name="action" value="add">
					<input type="hidden" name="item" value="'.$good_id.'">
					<input type="hidden" name="pselect" value="">
					<input type="hidden" name="refid" value="">
					</form>';
				}
				
				$basket_in_post .= '</td>';
				$basket_in_post .= '</tr></table>';
				
			}
			if ($option_main['basket_btn_inpost_additional_txt_align'] == 'right') {
				
				$basket_in_post .= '<table class="btn_inpost_add_txt" style=";width:100%;"><tr>';
				$basket_in_post .= '<td align="left">';
				
				// быстрая покупка
				if ($option_main['fast_buy']) {
					$basket_in_post .= '<form action="http://www.ecommtools.com/cgi-bin/cart.cgi" method="post" target="_blank" style="padding:0px;margin:0px">
					<input type="image" src="'.$basket_btn_url.'" alt="Купить">
					<input type="hidden" name="uid" value="'.$option_main['ect_shop_id'].'">
					<input type="hidden" name="action" value="buy">
					<input type="hidden" name="item" value="'.$good_id.'">
					<input type="hidden" name="pselect" value="">
					<input type="hidden" name="refid" value="">
					</form>';
				}
				// применения корзины заказов
				else {
					$basket_in_post .= '<form action="http://www.ecommtools.com/cgi-bin/cart.cgi" method="post" target="_blank" style="padding:0px;margin:0px">
					<input type="image" src="'.$basket_btn_url.'" alt="Добавить в корзину">
					<input type="hidden" name="uid" value="'.$option_main['ect_shop_id'].'">
					<input type="hidden" name="action" value="add">
					<input type="hidden" name="item" value="'.$good_id.'">
					<input type="hidden" name="pselect" value="">
					<input type="hidden" name="refid" value="">
					</form>';
				}
				
				$basket_in_post .= '</td>';
				$basket_in_post .= '<td align="left">'.$additional_inpostbutton_txt.'</td>';
				
				$basket_in_post .= '</tr></table>';
				
			}
				
			$basket_in_post .= '</div>';
			$basket_in_post .= '<div class="clear"></div>';
			
		}
		// без дополнительного текста
		else {
			$basket_in_post = '<div class="basket_box_inpost">';
			// быстрая покупка
				if ($option_main['fast_buy']) {
					$basket_in_post .= '<form action="http://www.ecommtools.com/cgi-bin/cart.cgi" method="post" target="_blank" style="padding:0px;margin:0px">
					<input type="image" src="'.$basket_btn_url.'" alt="Купить">
					<input type="hidden" name="uid" value="'.$option_main['ect_shop_id'].'">
					<input type="hidden" name="action" value="buy">
					<input type="hidden" name="item" value="'.$good_id.'">
					<input type="hidden" name="pselect" value="">
					<input type="hidden" name="refid" value="">
					</form>';
				}
				// применения корзины заказов
				else {
					$basket_in_post .= '<form action="http://www.ecommtools.com/cgi-bin/cart.cgi" method="post" target="_blank" style="padding:0px;margin:0px">
					<input type="image" src="'.$basket_btn_url.'" alt="Добавить в корзину">
					<input type="hidden" name="uid" value="'.$option_main['ect_shop_id'].'">
					<input type="hidden" name="action" value="add">
					<input type="hidden" name="item" value="'.$good_id.'">
					<input type="hidden" name="pselect" value="">
					<input type="hidden" name="refid" value="">
					</form>';
				}
			$basket_in_post .= '</div>';
			$basket_in_post .= '<div class="clear"></div>';
		}
		
		return $basket_in_post;
		
	}
	
	function insert_ectform_styles() {
		?>
		<style>
		.ecommtools_loginform_input{
			border-style:none;
			border-width:0;
			width:190px;
			height:30px;
			background:url(<?php echo UWCS_WP_PLUGIN_IMG_URL; ?>loginform_input.png);
			background-repeat:no-repeat;
			font-family:Tahoma,Verdana,Arial;
			font-size:13pt;
			color:#61687c;
			text-align:center;
			padding:0;
			margin-top:10px;	
		}
	
		.ecommtools_loginform_label{
		line-height: 31px;
		margin: 8px 115px;
		position: absolute;
		color:Grey;
		font-family:Verdana,Arial;
		font-size:9pt;
		}
		</style>
		<?php
	}

}

$UWPC = new UWPCstart();

?>