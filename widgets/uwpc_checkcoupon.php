<?php

class UWCCheckCoupon extends WP_Widget {


    function UWCCheckCoupon() {
		
		$id_base = 'uwccoupon';
		$widget_ops = array('classname' => 'uwccoupon', 'description' => __( 'Проверка купона скидки.', 'uwcart-start') );
		$widget_control_ops = array('title' => 'Проверка купона скидки', 'show' => 'all', 'slug' => '', 'slug_exept' => '', '_multiwidget' => '0');
		//$widget_control_ops = array();
		$this->WP_Widget($id_base, __('UWСart-S: Проверка купона скидки', 'uwccoupon'), $widget_ops, $widget_control_ops);
		
    }
	
    function widget($args, $instance) {
	
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$option_main = get_option('uwcs_main');
		$shop_id = $option_main['ect_shop_id'];
		
		$show = 'all';
		$slug = '';
		$slug_exept = '';
		
		$optsarr = $this->get_settings();
		
		foreach($optsarr as $opts) {
			if (array_key_exists('show', $opts)) $show = $opts['show'];
			if (array_key_exists('slug', $opts)) $slug = $opts['slug'];
			if (array_key_exists('slug_exept', $opts)) $slug_exept = $opts['slug_exept'];
		}

		$checkcoupon = $before_widget.$before_title.$title.$after_title;
		
		$checkcoupon .= '<table align="left" class="uwc_checkcouponform" cellspacing="0" cellpadding="0">';
		$checkcoupon .= '<form method="post" name="uwc_checkcoupon_form" style="margin:0px; padding:0px;">';
		$checkcoupon .= '<tr align="left"><td colspan="2">код скидки</td></tr>';
		$checkcoupon .= '<tr>';
		$checkcoupon .= '<td width="67%"><input type="text" name="id" style="width:99%;" /></td>';
		$checkcoupon .= '<td><input class="uwc_checkcoupon_button" type="button" value="Проверить" onclick="javascript:if(!document.uwc_checkcoupon_form.id.value){alert(\'Введите ваш код скидки\');document.uwc_checkcoupon_form.id.focus();return false;} jQuery(\'#popup_basket\').toggleCoupon({osid:this.form.id});return false;" /></td>';
		$checkcoupon .= '</tr>';
		$checkcoupon .= '</form>';
		$checkcoupon .= '</table>';
		
		$checkcoupon .= $after_widget;
		
		// условия, при которых виджет выводится в сайдбар
			switch ($show) {
				// везде
				case "all": 
					echo $checkcoupon;		
				break;
				// только на главной странице
				case "home":
					if (is_home()) echo $checkcoupon;
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
					
					if ($InPost) echo $checkcoupon;
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
					
					if ($InCategory) echo $checkcoupon;
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
					
					if ($InCategory) echo $checkcoupon;
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
					
					if ($InPage) echo $checkcoupon;
				break;
			}

    }

    function update ($new_instance, $old_instance) {				
		
		if (function_exists('current_user_can') && !current_user_can('manage_options')) die( _e('Hacker?', 'ectexcerpts'));
		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['show'] = $new_instance['show'];
		$instance['slug'] = strip_tags($new_instance['slug']);
		$instance['slug_exept'] = strip_tags($new_instance['slug_exept']);
		
        return $instance;
    }

    function form($instance) {				
        
		$title = esc_attr($instance['title']);
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
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php if (!$title){$title = 'Проверка купона скидки';} echo $title; ?>" />
			</label>
			</p>
			<p>
			<label for="<?php echo $this->get_field_id('show'); ?>" title="Показывать только на определенной странице или в опреденной категории. По-умолчанию: везде." style="line-height:35px;">Где показывать виджет:<br />
			<select name="<?php echo $this->get_field_name('show'); ?>" id="<?php echo $this->get_field_id('show'); ?>" style="width:234px;">
			<option label="Везде" value="all" <?php if ($allSelected){echo "selected";} ?>>Везде</option>
			<option label="На главной" value="home" <?php if ($homeSelected){echo "selected";} ?>>На главной</option>
			<option label="В постах" value="post" <?php if ($postSelected){echo "selected";} ?>>В постах</option>
			<option label="В постах определенных категорий" value="post_in_category" <?php if ($postInCategorySelected){echo "selected";} ?>>В постах определенных категорий ID(s)</option>
			<option label="В определенных категориях" value="category" <?php if ($categorySelected){echo "selected";} ?>>В определенных категориях ID(s)</option>
			<option label="На определенных страницах" value="page" <?php if ($pageSelected){echo "selected";} ?>>На определенных страницах ID(s)</option>
			</select>
			</label><br />
			
			<label for="<?php echo $this->get_field_id('slug'); ?>"  title="Условия показа на определенных страницах, в постах или категориях" style="line-height:35px;">ID (slug) (через запятую):<br />
			<input type="text" style="width:235px;" id="<?php echo $this->get_field_id('slug'); ?>" name="<?php echo $this->get_field_name('slug'); ?>" value="<?php echo htmlspecialchars($slug); ?>" />
			</label>
			<table width="100%"><tr>
			<td><label for="<?php echo $this->get_field_id('slug_exept'); ?>">
			Кроме указанных:
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