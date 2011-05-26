<?php

class UWCMakeOrder extends WP_Widget {

    function UWCMakeOrder() {
		
		$id_base = 'uwcmakeorder';
		$widget_ops = array('classname' => 'uwcmakeorder', 'description' => __( 'Виджет оформления заказа.') );
		$widget_control_ops = array('title' => 'Купить', 'show' => 'all', 'slug' => '', 'slug_exept' => '', '_multiwidget' => '0');
		//$widget_control_ops = array();
		$this->WP_Widget($id_base, __('UWСart-S: Купить'), $widget_ops, $widget_control_ops);

    }
	
	function GetTheGoodInfo() {
		global $wpdb;
		
		$postID = 0;
		$optsarr = $this->get_settings();
		
		foreach($optsarr as $opts) {
			if (array_key_exists('single_item', $opts)) $single_item_id = $opts['single_item'];
		}
		
		if ($single_item_id) {

			$row = $wpdb->get_row( $wpdb->prepare("SELECT post_title, guid FROM $wpdb->posts WHERE ID=$single_item_id AND post_status='publish' LIMIT 0,1",$postID), ARRAY_A );
			
			if ($row['post_title']) {
				$postID = $single_item_id;
			}

		}
		else {
			$postID = get_the_ID();
			$row = $wpdb->get_row( $wpdb->prepare("SELECT post_title, guid FROM $wpdb->posts WHERE ID=$postID AND post_status='publish' LIMIT 0,1",$postID), ARRAY_A );
		}

		if ($postID) {
			
			$good_id = get_post_meta($postID, 'uwc_good_id', true);
			$price = get_post_meta($postID, 'uwc_cost', true);

			$good_info['good_id'] = $good_id;
			$good_info['cost'] = $price;
			$good_info['cost_in_exerpt'] = $uwc_cost_in_exerpt;
			$good_info['name'] = $row['post_title'];
			$good_info['url'] = $row['guid'];
			
			return $good_info;
		}
		else return false;
	}
	
	function widget($args, $instance) {
		
		$good_info = $this->GetTheGoodInfo();
		$good_id = $good_info['good_id'];
		
		$price = $good_info['cost'];
		//$good_type = $good_info['type'];
	
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$price_block = apply_filters('widget_title', $instance['price_block']);
		$addtext1 = $instance['addtext1'];
		$addtext2 = $instance['addtext2'];
		
		$show = 'all';
		$slug = '';
		$slug_exept = '';
		
		$show = apply_filters('widget_title', $instance['show']);
		$slug = apply_filters('widget_title', $instance['slug']);
		$slug_exept = apply_filters('widget_title', $instance['slug_exept']);
		
		$option_main = get_option('uwcs_main');
		$shop_id = $option_main['ect_shop_id'];
		$currency_type = $option_main['ect_currency_type'];

		if ($option_main['basket_btn_own']) $basket_btn_url = $option_main['basket_btn_own'];
		elseif ($option_main['basket_btn']) $basket_btn_url = $option_main['basket_btn'];
		else $basket_btn_url = UWCS_DEFAULT_BASKET_BUTTON;
		
		if ($option_main['order_btn_own']) $order_btn_url = $option_main['order_btn_own'];
		elseif ($option_main['order_btn']) $order_btn_url = $option_main['order_btn'];
		else $order_btn_url = UWCS_DEFAULT_ORDER_BUTTON;
	
		if ($good_id) $title = str_replace("%ITEM%",$good_info['name'],$title);
		else $title = 'Корзина';
			
		// если виджет отображается не на странице с описанием товара, делаем ссылку на страницу описания
		if ($addtext1) {
			if (is_single() || is_page()) {
				if ($good_id == get_the_ID()) $etc_addtext1_replacement1 = $good_info['name'];
				else $etc_addtext1_replacement1 = '<a href="'.$good_info['url'].'">'.$good_info['name'].'</a>';
			}
		}
			
		$etc_addtext1 = str_replace("%ITEM%",$etc_addtext1_replacement1,$addtext1);
		$etc_addtext1_replacement2 = '<span class="order_vidget_price_in_additional_text">'.$price.' '.$currency_type.'</span>';
		$etc_addtext1 = str_replace("%PRICE%",$etc_addtext1_replacement2,$etc_addtext1);
		$etc_addtext2 = $addtext2;
		
		$vidget_output = $before_widget.$before_title.$title.$after_title;
			
		$vidget_output .= '<div class="order_vidget" align="center">';
		
		if ($good_id) {
			
		// дополнительный текст над кнопкой добавления в корзину		
		if ($etc_addtext1) {
			$vidget_output .= '<div class="order_vidget_additional_txt">'.$etc_addtext1.'</div>';
		}
			
		if ($price_block) {
			if ($price) {
				$vidget_output .= '<div class="order_vidget_price">'.$price.' <span>'.$currency_type.'</span></div>';
			}
			else {
				$vidget_output .= '<p style="color:red;">'._e("Цена не определена").'</p>';
			}
		}
					
		// быстрая покупка
		if ($option_main['fast_buy']) {
			$vidget_output .= '<div class="order_vidget_order_btn"><form action="http://www.ecommtools.com/cgi-bin/cart.cgi" method="post" target="_blank" style="padding:0px;margin:0px">
			<input type="image" src="'.$basket_btn_url.'" alt="Купить">
			<input type="hidden" name="uid" value="'.$option_main['ect_shop_id'].'">
			<input type="hidden" name="action" value="buy">
			<input type="hidden" name="item" value="'.$good_id.'">
			<input type="hidden" name="pselect" value="">
			<input type="hidden" name="refid" value="">
			</form></div>';
		}
		// применения корзины заказов
		else {
			$vidget_output .= '<div class="order_vidget_buy_btn"><form action="http://www.ecommtools.com/cgi-bin/cart.cgi" method="post" target="_blank" style="padding:0px;margin:0px">
			<input type="image" src="'.$basket_btn_url.'" alt="Добавить в корзину">
			<input type="hidden" name="uid" value="'.$option_main['ect_shop_id'].'">
			<input type="hidden" name="action" value="add">
			<input type="hidden" name="item" value="'.$good_id.'">
			<input type="hidden" name="pselect" value="">
			<input type="hidden" name="refid" value="">
			</form></div>';
					
			// кнопка "оформить заказ"
			$vidget_output .= '<div class="order_vidget_order_btn">
			<form action="http://www.ecommtools.com/cgi-bin/cart.cgi" method="post" target="_blank">
			<input type="hidden" name="uid" value="'.$option_main['ect_shop_id'].'">
			<input type="hidden" name="action" value="view">
			<input type="image" src="'.$order_btn_url.'" alt="Оформить заказ">
			</form>
			</div>';
		}

		// дополнительный текст под кнопкой оформления заказа
		if ($etc_addtext2) {
			$vidget_output .= '<div class="order_vidget_additional_txt" style="text-align:center;">'.$etc_addtext2.'</div>';
		}
		
		}
		else {
		
			// кнопка "оформить заказ"
			$vidget_output .= '<br /><div class="order_vidget_order_btn">
			<form action="http://www.ecommtools.com/cgi-bin/cart.cgi" method="post" target="_blank">
			<input type="hidden" name="uid" value="'.$option_main['ect_shop_id'].'">
			<input type="hidden" name="action" value="view">
			<input type="image" src="'.$order_btn_url.'" alt="Оформить заказ">
			</form>
			</div>';
		
		}
					
		$vidget_output .= '</div>';
			
		$vidget_output .= $after_widget;
		
		// условия, при которых виджет выводится в сайдбар
			switch ($show) {
				// везде
				case "all": 
					echo $vidget_output;		
				break;
				// только на главной странице
				case "home":
					if (is_home()) echo $vidget_output;
				break;
				// в постах
				case "post":
					// в определенных постах
					// если не указыватьID`s постов, выводится во всех постах
					
					// если прописано исключение - выводится во всех постах, кроме указанных
					if ($slug_exept && is_single()) {
						$PST = explode(",",$slug);
						$InPost = true;
						$current_post_id = get_the_ID();

						foreach($PST as $PostID) {
							if(is_single($PostID)){
								$InPost = false;
							}
						}
					}
					else {
						$PST = explode(",",$slug);
						$InPost = false;

						foreach($PST as $PostID) {
							if(is_single($PostID)){
								$InPost = true;
							}
						}
					}
					
					if ($InPost) echo $vidget_output;
				break;
				// в постах определенных категорий
				// ID`s категорий указывать обязательно 
				case "post_in_category":
					if ($slug_exept && is_single()) {
						$PiC = explode(",",$slug);
						$InCategory = true;
						foreach($PiC as $CategoryID) {
							if(in_category($CategoryID)){
								$InCategory = false;
							}
						}
					}
					else {
						$PiC = explode(",",$slug);
						$InCategory = false;
						foreach($PiC as $CategoryID) {
							if(is_single() && in_category($CategoryID)){
								$InCategory = true;
							}
						}
					}
					
					if ($InCategory) echo $vidget_output;
				break;
				// в определенных категориях
				// для единственного товара - ID товара указывать обязательно!
				// (если не указыватьID`s категорий, выводится во всех категориях)
				case "category":
					if ($slug_exept && !is_single()) {
						$CAT = explode(",",$slug);
						$InCategory = true;
						foreach($CAT as $CategoryID) {
							if(is_category($CategoryID)){
								$InCategory = false;
							}
						}
					}
					else {
						$CAT = explode(",",$slug);
						$InCategory = false;
						foreach($CAT as $CategoryID) {
							if(is_category($CategoryID)){
								$InCategory = true;
							}
						}
					}
					
					if ($InCategory) echo $vidget_output;
				break;
				// на определенных страницах
				// для единственного товара - ID товара указывать обязательно!
				// (если не указыватьID`s страниц, выводится на всех страницах)
				case "page":
					if ($slug_exept && is_page()) {
						$PG = explode(",",$slug);
						$InPage = true;
						foreach($PG as $PageID) {
							if(is_page($PageID)){
								$InPage = false;
							}
						}
					}
					else {
						$PG = explode(",",$slug);
						$InPage = false;
						foreach($PG as $PageID) {
							if(is_page($PageID)){
								$InPage = true;
							}
						}
					}
					
					if ($InPage) echo $vidget_output;
				break;
			}

    }

    function update ($new_instance, $old_instance) {				
		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['addtext1'] = strip_tags($new_instance['addtext1'],'<strong><span><br><a>');
		$instance['addtext2'] = strip_tags($new_instance['addtext2'],'<strong><span><br><a>');
		$instance['price_block'] = $new_instance['price_block'];
		$instance['single_item'] = strip_tags($new_instance['single_item']);
		$instance['show'] = $new_instance['show'];
		$instance['slug'] = strip_tags($new_instance['slug']);
		$instance['slug_exept'] = strip_tags($new_instance['slug_exept']);
		
        return $instance;
    }

    function form($instance) {				
        
		$title = esc_attr($instance['title']);
		$addtext1 = esc_attr($instance['addtext1']);
		$addtext2 = esc_attr($instance['addtext2']);
		$price_block = esc_attr($instance['price_block']);
		$single_item = esc_attr($instance['single_item']);
		$show = esc_attr($instance['show']);
		$slug = esc_attr($instance['slug']); 
		$slug_exept = esc_attr($instance['slug_exept']); 

		$allSelected = $homeSelected = $postSelected = $postInCategorySelected = $pageSelected = $categorySelected = false;
		
		switch ($show) {
			case "all":
			$allSelected = true;
			break;
			case "":
			$allSelected = true;
			break;
			case "home":
			$homeSelected = true;
			break;
			case "post":
			$postSelected = true;
			break;
			case "post_in_category":
			$postInCategorySelected = true;
			break;
			case "category":
			$categorySelected = true;
			break;
			case "page":
			$pageSelected = true;
			break;
		}

        ?>	
			<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?>: 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php if (!$title){$title = 'Купить';} echo $title; ?>" /><br />
			<em><small>* <?php _e('Для указания названия товара в заголовке виджета, используйте маркер %ITEM%. Например:<br /><span style="color:#2C6DC8;"><code>Купить %ITEM%</code></span>'); ?></small></em>
			</label>
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('addtext1'); ?>"><?php _e('Дополнительный текст над кнопкой добавления в корзину'); ?>:<br />
			<input class="widefat" id="<?php echo $this->get_field_id('addtext1'); ?>" name="<?php echo $this->get_field_name('addtext1'); ?>" type="text" value="<?php echo $addtext1; ?>" /><br />
			<em><small>* <?php _e('Разрешены теги "strong", "br", "span" и "a". Для указания названия товара в виджете, используйте маркер %ITEM%. Для указания цены товара в дополнительном тексте используйте маркер %PRICE%. Например:'); ?><br /><span style="color:#2C6DC8;"><code><?php echo htmlspecialchars(UWCS_DEFAULT_VIDGET_TEXT1); ?></code></span></small></em>
			</label>
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('price_block'); ?>"><?php _e('Цена в отдельном блоке'); ?>:&nbsp;
			<input type="checkbox" id="<?php echo $this->get_field_id('price_block'); ?>" name="<?php echo $this->get_field_name('price_block'); ?>" value="1"<?php if ($price_block) echo ' checked="checked"'; ?> />
			</label>
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('addtext2'); ?>"><?php _e('Дополнительный текст под кнопкой оформления заказа'); ?>:<br />
			<input class="widefat" id="<?php echo $this->get_field_id('addtext2'); ?>" name="<?php echo $this->get_field_name('addtext2'); ?>" type="text" value="<?php echo $addtext2; ?>" /><br />
			<em><small>* <?php _e('Разрешены теги "strong", "br", "span" и "a". Для указания названия товара в виджете, используйте маркер %ITEM%. Для указания цены товара в дополнительном тексте используйте маркер %PRICE%. Например:'); ?><br /><span style="color:#2C6DC8;"><code><?php echo htmlspecialchars(UWCS_DEFAULT_VIDGET_TEXT2); ?></code></span></small></em>
			</label>
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('single_item'); ?>"><?php _e('Специальный товар (ID поста или страницы)'); ?>: 
			<input class="widefat" id="<?php echo $this->get_field_id('single_item'); ?>" name="<?php echo $this->get_field_name('single_item'); ?>" type="text" value="<?php echo $single_item; ?>" /><br />
			<em><small>* <?php _e('Вы можете указать здесь идентификатор поста или страницы с описанием определенного товара, который будет эксклюзивно предлагаться для заказа в этом виджете'); ?></small></em>
			</label>
			</p>
			
			<p>
			<label for="<?php echo $this->get_field_id('show'); ?>" title="<?php _e('Показывать только на определенной странице или в опреденной категории. По-умолчанию: везде.'); ?>" style="line-height:35px;"><?php _e('Где показывать виджет'); ?>:<br />
			<select name="<?php echo $this->get_field_name('show'); ?>" id="<?php echo $this->get_field_id('show'); ?>" class="widefat">
			<option label="<?php _e('Везде'); ?>" value="all" <?php if ($allSelected){echo "selected";} ?>><?php _e('Везде'); ?></option>
			<option label="<?php _e('На главной'); ?>" value="home" <?php if ($homeSelected){echo "selected";} ?>><?php _e('На главной'); ?></option>
			<option label="<?php _e('В постах'); ?>" value="post" <?php if ($postSelected){echo "selected";} ?>><?php _e('В постах'); ?></option>
			<option label="<?php _e('В постах определенных категорий'); ?>" value="post_in_category" <?php if ($postInCategorySelected){echo "selected";} ?>><?php _e('В постах определенных категорий ID(s)'); ?></option>
			<option label="<?php _e('В определенных категориях'); ?>" value="category" <?php if ($categorySelected){echo "selected";} ?>><?php _e('В определенных категориях ID(s)'); ?></option>
			<option label="<?php _e('На определенных страницах'); ?>" value="page" <?php if ($pageSelected){echo "selected";} ?>><?php _e('На определенных страницах ID(s)'); ?></option>
			</select>
			</label><br />
			
			<label for="<?php echo $this->get_field_id('slug'); ?>"  title="<?php _e('Условия показа на определенных страницах, в постах или категориях'); ?>" style="line-height:35px;"><?php _e('ID (slug) (через запятую)'); ?>:<br />
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('slug'); ?>" name="<?php echo $this->get_field_name('slug'); ?>" value="<?php echo htmlspecialchars($slug); ?>" />
			</label>
			<table width="100%"><tr>
			<td><label for="<?php echo $this->get_field_id('slug_exept'); ?>">
			<?php _e('Кроме указанных'); ?>:
			</label>
			</td>
			<td align="right">
			<input type="checkbox" id="<?php echo $this->get_field_id('slug_exept'); ?>" name="<?php echo $this->get_field_name('slug_exept'); ?>" value="1"<?php if ($slug_exept) echo ' checked="checked"'; ?> />
			</td>
			</tr>
			</table>
			</p>

        <?php 

    }

}
?>