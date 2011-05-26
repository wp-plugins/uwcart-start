<?php

class UWCPromo extends WP_Widget {


    function UWCPromo() {
		
		$id_base = 'uwcpromo';
		$widget_ops = array('classname' => 'uwcpromo', 'description' => __( 'Разместите виджет рекомендации плагина UWCart на своем блоге и получайте за это деньги!', 'uwcart-start') );
		$widget_control_ops = array('title' => '', 'show' => 'all', 'slug' => '', 'slug_exept' => '', '_multiwidget' => '0');
		$this->WP_Widget($id_base, __('UWСart: рекламный блок', 'uwcpromo'), $widget_ops, $widget_control_ops);
		
    }
	
    function widget($args, $instance) {
	
        extract( $args );
		
        $title = apply_filters('widget_title', $instance['title']);
		$promobutton = apply_filters('promobutton', $instance['promobutton']); 
		$uwc_refid = apply_filters('uwc_refid', $instance['uwc_refid']);
		$option_main = get_option('uwcs_main');
		if (!$uwc_refid) $uwc_refid = $option_main['uwc_refid'];
		if ($uwc_refid) $reflink = 'http://uwcart.ru/promo/'.$uwc_refid;
		else $reflink = 'http://uwcart.ru/';
		
		$show = 'all';
		$slug = '';
		$slug_exept = '';
		
		$optsarr = $this->get_settings();
		
		foreach($optsarr as $opts) {
			if (array_key_exists('show', $opts)) $show = $opts['show'];
			if (array_key_exists('slug', $opts)) $slug = $opts['slug'];
			if (array_key_exists('slug_exept', $opts)) $slug_exept = $opts['slug_exept'];
		}

		$promocontent = $before_widget.$before_title.$title.$after_title;
		
		$promocontent .= '<div id="uwc_promo_widget_box" align="center">';		
		$promocontent .= '<a href="'.$reflink.'" target="_blank" title="UWCart: плагин магазина на WordPress"><img src="'.$promobutton.'" alt="UWCart: плагин магазина на WordPress" class="uwc_promo_widget_img" style="border:none;" /></a>';
		$promocontent .= '</div>';
		
		$promocontent .= $after_widget;
		
		// условия, при которых виджет выводится в сайдбар
			switch ($show) {
				// везде
				case "all": 
					echo $promocontent;		
				break;
				// только на главной странице
				case "home":
					if (is_home()) echo $promocontent;
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
					
					if ($InPost) echo $promocontent;
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
					
					if ($InCategory) echo $promocontent;
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
					
					if ($InCategory) echo $promocontent;
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
					
					if ($InPage) echo $promocontent;
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
		$instance['promobutton'] = $new_instance['promobutton'];
		$instance['uwc_refid'] = $new_instance['uwc_refid'];
		
        return $instance;
    }

    function form($instance) {				
        
		$title = esc_attr($instance['title']);
		$show = esc_attr($instance['show']);
		$slug = esc_attr($instance['slug']); 
		$slug_exept = esc_attr($instance['slug_exept']); 
		$addinvimg = esc_attr($instance['promobutton']);
		$uwc_refid = esc_attr($instance['uwc_refid']);
		$option_main = get_option('ecommtools_main');
		$uwc_refid = $option_main['uwc_refid'];

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
			
			<?php if ($uwc_refid) : ?>
			<p>Ваш refID: <span style="color:#289EF9;"><strong><?php echo $uwc_refid; ?></strong></span></p>
			<?php else : ?>
			<p>Зарегистрируйтесь в <a href="http://uwcart.ru/affiliate.html" target="_blank">партнерской программе</a> проекта UWCart.ru и получайте деньги за рекомендацию плагина!</p>
			<p><span style="color:red;">Укажите, пожалуйста, Ваш <strong>refID</strong> в разделе <a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=uwpcmainsettings">&laquo;Установки&raquo;</a>!</span><p>
			<?php endif; ?>
			
			<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php if (!$title){$title = '';} echo $title; ?>" />
			</label>
			</p>
			<p>
			<label for="<?php echo $this->get_field_id('promobutton'); ?>"><?php _e('Вид рекламной кнопки:'); ?><br />
			<span style="font-size:80%;"><em>(масштаб 1:2)</em></span>
			<table width="100%">
			
			<?php
			$etcimgdir = UWCS_WP_PLUGIN_IMG_INVIDGET_DIR.'promo/';
			$opendir = opendir($etcimgdir);
					
			$e = 0;
			while ($vimg = readdir($opendir)) {
				$btninfo = @getimagesize($etcimgdir.$vimg);		
				if ($vimg != "." && $vimg != ".." && $btninfo) { 
					$invpicturl = UWCS_WP_PLUGIN_IMG_INVIDGET_URL.'promo/'.$vimg;
					
					$btnwidth = floor($btninfo[0]/2);

				?>
					<tr valign="middle">
					<td height="58"><img src="<?php echo $invpicturl; ?>" border="none" width="<?php echo $btnwidth; ?>" /></td>
					<td width="10"><input type="radio" name="<?php echo $this->get_field_name('promobutton'); ?>" id="<?php echo $this->get_field_id('promobutton'); ?>_<?php echo $e; ?>" value="<?php echo $invpicturl; ?>" <?php if ((!$addinvimg && $e == 0) || ($invpicturl == $addinvimg)) { echo ' checked="checked"'; } ?> /></td>
					</tr>
				<?php 
					$e++;
				}
				
			}
			
			closedir($opendir);
			?>
			</table>
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