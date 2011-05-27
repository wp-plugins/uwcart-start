<?php

// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

class UWCart extends UWPCstart {
	
	function mainsettings_form_fields() {
		
		$fields = array(
			
			array(	"name" => "Вариант кнопки добавления товара в корзину (в посте)",
					"desc" => "",
					"id" => "",
					"std" => "",
					"type" => "lable"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "basket_btn_inpost",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."buy1.png",
					"type" => "radio"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "basket_btn_inpost",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."buy1_1.png",
					"type" => "radio"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "basket_btn_inpost",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."buy1_2.png",
					"type" => "radio"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "basket_btn_inpost",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."buy1_3.png",
					"type" => "radio"
			),
			
			array(	"name" => "... или укажите свою",
					"desc" => "* URL Вашей кнопки",
					"id" => "basket_btn_inpost_own",
					"std" => "",
					"type" => "text"
			),
			

			array(	"name" => "Дополнительный текст возле кнопки корзины в посте",
					"desc" => "* Разрешены теги &laquo;strong&raquo;, &laquo;br&raquo;, &laquo;span&raquo; и &laquo;a&raquo;. Для указания названия товара, используйте маркер %ITEM%, для указания стоимости товара в дополнительном тексте используйте маркер %PRICE%. Например:<br /><span style='color:#2C6DC8;'><code>".htmlspecialchars(UWCS_DEFAULT_VIDGET_TEXT1)."</code></span>",
					"id" => "basket_btn_inpost_additional_txt",
					"std" => "",
					"type" => "textarea"
			),
			
			array(	"name" => "Выравнивание дополнительного текста относительно кнопки",
				"desc" => "",
				"id" => "basket_btn_inpost_additional_txt_align",
				"std" => "left",
				"options" => array('left'=>'Слева','right'=>'Справа'),
				"type" => "select"
			),
			
			array(	"name" => "Быстрая покупка",
					"desc" => "* Корзина не используется. Покупатель сразу переходит к оформлению заказа.",
					"id" => "fast_buy",
					"std" => "1",
					"type" => "checkbox"
			),
			
			array(	"name" => "Вариант кнопки добавления товара в корзину (в виджете)",
					"desc" => "",
					"id" => "",
					"std" => "",
					"type" => "lable"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "basket_btn",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."buy1.png",
					"type" => "radio"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "basket_btn",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."buy1_1.png",
					"type" => "radio"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "basket_btn",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."buy1_2.png",
					"type" => "radio"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "basket_btn",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."buy1_3.png",
					"type" => "radio"
			),
			
			array(	"name" => "... или укажите свою",
					"desc" => "* URL Вашей кнопки",
					"id" => "basket_btn_own",
					"std" => "",
					"type" => "text"
			),
			
			array(	"name" => "Вариант кнопки оформления заказа (в виджете)",
					"desc" => "",
					"id" => "order_btn_lbl",
					"std" => "",
					"type" => "lable"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "order_btn",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."button_checkout_1.png",
					"type" => "radio"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "order_btn",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."button_checkout_2.png",
					"type" => "radio"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "order_btn",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."button_checkout_3.png",
					"type" => "radio"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "order_btn",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."button_checkout_4.png",
					"type" => "radio"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "order_btn",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."button_checkout_5.png",
					"type" => "radio"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "order_btn",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."button_checkout_6.png",
					"type" => "radio"
			),
			
			array(	"name" => "",
					"desc" => "",
					"id" => "order_btn",
					"std" => UWCS_WP_PLUGIN_IMG_BUTTONS_URL."button_checkout_7.png",
					"type" => "radio"
			),
			
			array(	"name" => "... или укажите свою",
					"desc" => "* URL Вашей кнопки",
					"id" => "order_btn_own",
					"std" => "",
					"type" => "text"
			),
			
			array(	"name" => "Ваш ID в сервисе EcommTools",
					"desc" => "* Логин, указанный при регистрации в сервисе.",
					"id" => "ect_shop_id",
					"std" => "",
					"type" => "text"
			),
			
			array(	"name" => "Ваш идентификатор партнера (refID)",
						"desc" => "* Зарегистрируйтесь в <a href=\"http://uwcart.ru/affiliate.html\" target=\"_blank\">партнерской программе</a> проекта UWCart.ru и получайте деньги за рекомендацию профессиональной версии плагина UWCart!",
						"id" => "uwc_refid",
						"std" => "",
						"type" => "text"
				),

			array(	"name" => "Тип валюты при выводе цены товара",
					"desc" => "* Например: USD, EUR, RUB, UAH, руб., грн. (будет отображаться после цены).",
					"id" => "ect_currency_type",
					"std" => "",
					"type" => "text"
			),
			
		);
		
		return $fields;
	}

	
	function mainsettings_form() {
		
		$admin_form_fields = $this->mainsettings_form_fields();
		$option_main = get_option('uwcs_main');
		
		if(isset($_POST['action']) && $_POST['action'] == 'save_mainsettings') {
			
			if ($this->mainsettings()) {
				$this->redirect($_SERVER['PHP_SELF'].'?page=uwpcmainsettings&saved=true');
			}

		}
		
		if ( isset($_REQUEST['saved']) && $_REQUEST['saved'] ) {
			
			echo '<div id="message" class="updated fade"><p><strong>Данные настроек сохранены</strong></p></div>';
			
		}

		?>
		
		<div class="wrap">
		
		<h2>Ultimate WordPress Cart Start</h2>
		<div class="updated fade" style="background:#E2EEF8;border-color:#96CEF9;"><p><strong><a href="http://uwcart.ru/" target="_blank">Кликните здесь, чтобы узнать о возможностях профессиональной версии плагина UWCart</a></strong></p></div>
		<form method="post" name="ECTsettingsform" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=uwpcmainsettings">
		<table style="width:50%;margin-top:10px;">
		<tr>
		<td valign="top">
		
		<div class='meta-box-sortables'> 
		<div class="postbox"> 
		<div class="handlediv" title=""></div><h3 class='hndle' style="padding:5px 0 10px 10px;margin:0;font-size:90%;"><span>Основные настройки</span></h3> 
		<div class="inside">

		<input type="hidden" name="action" value="save_mainsettings" />
		<table style="width:100%;padding:20px;">

		<?php
		$n = 0;
		foreach ($admin_form_fields as $value) {
			switch ( $value['type'] ) {
				
				case 'radio': ?>
				<tr>
				<td colspan="2">
				<?php
				if ($value['id'] == 'order_btn') {
				?>
				<div id="order_btn_box_<?php echo $n; ?>">
				<?php
				}
				?>
				<table style="width:100%;">
				<tr>
				<td align="right">
				<img src="<?php echo $value['std']; ?>" border="none" />
				</td>
				<td width="100" style="padding-left:20px;">
				<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']."_".$n; ?>" type="<?php echo $value['type']; ?>" value="<?php echo $value['std']; ?>"<?php if($option_main[$value['id']]==$value['std']) echo ' checked="checked"';?> onclick="clearField();" />
				</td>
				</tr>
				</table>
				<?php
				if ($value['id'] == 'order_btn' && get_settings('order_btn')) {
				?>
				</div>
				<?php
				}
				?>
				</td>
				</tr>
				
				<?php
				break;
				
				case 'text': 
				if ($value['id'] == 'ect_currency_type') {
				?>
					<tr style="background:#eee;">
					<td style="padding:5px;">
					<?php echo $value['name']; ?>:
					</td>
					<td align="right">
					<input style="width:50px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']."_".$n; ?>" type="<?php echo $value['type']; ?>" 
					value="<?php if ( $option_main[$value['id']] != "") { echo $option_main[$value['id']]; } else { echo $value['std']; }?>" />
					</td>
					</tr>
					<tr>
					<td colspan="2">
					<small><?php echo $value['desc']; ?></small>
					<br /><br />
					</td>
					</tr>
				<?php
				}
				else {
				?>
				<tr>
				<td colspan="2" style="padding:5px;">
				<?php echo $value['name']; ?>:<br />
				<input style="width:500px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']."_".$n; ?>" type="<?php echo $value['type']; ?>" 
				value="<?php if ( $option_main[$value['id']] != "") { echo $option_main[$value['id']]; } else { echo $value['std']; }?>"<?php if ($value['id'] == 'basket_btn_own') { echo ' onclick="clearRadioGroup1(1);"'; } if ($value['id'] == 'basket_btn_inpost_own') { echo ' onclick="clearRadioGroup1(0);"'; } if ($value['id'] == 'order_btn_own') { echo ' onclick="clearRadioGroup2();"'; } ?> />
				<br />
				<?php
				if ($value['id'] == 'basket_btn_own' && $option_main['basket_btn_own']) {
				?>
					</td>
					</tr>
					<tr>
					<td colspan="2" style="padding:4px;border:1px solid #d9d9d9;text-align:center;">
					<small>* Ваша кнопка добавления товара в корзину:</small><br />
					<img src="<?php echo $option_main['basket_btn_own']; ?>" border="none" />
					</td>
					</tr>
					<tr>
					<td colspan="2">
					<br /><br />
					</td>
					</tr>
				<?php
				}
				elseif ($value['id'] == 'basket_btn_inpost_own' && $option_main['basket_btn_inpost_own']) {
				?>
					</td>
					</tr>
					<tr>
					<td colspan="2" style="padding:4px;border:1px solid #d9d9d9;text-align:center;">
					<small>* Ваша кнопка добавления товара в корзину (в посте):</small><br />
					<img src="<?php echo $option_main['basket_btn_inpost_own']; ?>" border="none" />
					</td>
					</tr>
					<tr>
					<td colspan="2">
					<br /><br />
					</td>
					</tr>
				<?php
				}
				elseif ($value['id'] == 'order_btn_own' && $option_main['order_btn_own']) {
				?>
					</td>
					</tr>
					<tr>
					<td colspan="2" style="padding:4px;border:1px solid #d9d9d9;text-align:center;width:500px;">
					<small>* Ваша кнопка оформления заказа:</small><br />
					<img src="<?php echo $option_main['order_btn_own']; ?>" border="none" />
					</td>
					</tr>
					<tr>
					<td colspan="2">
					<br /><br />
					</td>
					</tr>
				<?php
				}
				else {
				?>
				<small><?php echo $value['desc']; ?></small>
				<br /><br />
				</td>
				</tr>
				<?php
				}
				}
				?>
				<?php
			
				break;
				
				case 'lable': ?>
				
				<tr>
				<td colspan="2" style="padding:5px;">&nbsp;</td>
				</tr>
				<tr style="background:#eee;">
				<td colspan="2" style="padding:5px;"><?php echo $value['name']; ?>:</td>
				</tr>
				<?php
				break;
				
				case 'textarea':?>
				<tr style="background:#eee;">
				<td colspan="2" style="padding:5px;"><?php echo $value['name']; ?>:</td>
				</tr>
				<tr>
				<td colspan="2">
				<textarea name="<?php echo $value['id']; ?>" style="width:100%;height:50px;text-align:left;padding:5px;color:#666;font-style:Arial;"><?php if($option_main[$value['id']]) echo $option_main[$value['id']];?></textarea>
				</tr>
				<tr>
				<td colspan="2" style="padding:3px;border:1px solid #d9d9d9;line-height:17px;"><small><?php echo $value['desc']; ?></small></td>
				</tr>
				<?php
				break;
				
				case 'select': ?>
				<tr>
				<td colspan="2">
				<table style="width:100%;">
				<tr>
				<td><small><?php echo $value['name']; ?>:</small></td>
				<td align="right">
				<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
				<?php while ($option = each($value['options'])) { ?>
				<option value="<?php echo $option['key']; ?>"<?php if($option_main[$value['id']]==$option['key']) echo ' selected="selected"';
				else if ($option['key']==$value['std'] && $option_main[$value['id']]=='') echo ' selected="selected"'; ?>><?php echo $option['value']; ?></option>
				<?php } ?>
				</select>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				<tr>
				<td colspan="2"><br /><br /></td>
				</tr>
				<?php
				break;
				
				case 'checkbox': ?>
				<tr style="background:#eee;padding:5px;">
				<td colspan="2">
				<table style="width:100%;">
				<tr>
				<td style="padding:3px;"><?php echo $value['name']; ?>:</td>
				<td align="right" style="padding:3px;">
				<?php 
				if ($value['id'] == 'fast_buy') { ?>
				<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="1"<?php if($option_main[$value['id']]==$value['std']) { echo ' checked="checked"'; } ?> onclick="clearRadioGroup2();checkFastBuy();" />
				<?php
				}
				?>
				
				</td>
				</tr>
				</table>
				
				</td>
				</tr>
				<tr>
				<td colspan="2" style="padding:3px; border:1px solid #d9d9d9;line-height:17px;"><small><?php echo $value['desc']; ?></small></td>
				</tr>
				<tr>
				<td colspan="2"><br /></td>
				</tr>
				<?php
				break;
				
				default:
				break;
			}
			
			$n++;
		}
		?>
		</table>
		</div>
		</div>
		</div>
		</div>
		</td>
		</tr>
		</table>
		<p class="submit" style="padding:10px;margin-bottom:50px;">
			<input name="save" type="submit" class="button-primary" value="Сохранить изменения" onclick="return checkECTSubmit();" />
		</p>
		</form>
		
		
		</div>
		<?php
		
	}
	
	function mainsettings() {

		if($_POST['action'] == 'save_mainsettings'){
			
			$mainsettings_form_fields = $this->mainsettings_form_fields();
			$option_main = array();
			
			foreach($mainsettings_form_fields as $value){
					
				if ($_REQUEST['basket_btn_own']) {
					$_REQUEST['basket_btn'] = '';
				}
				if ($_REQUEST['basket_btn_inpost_own']) {
					$_REQUEST['basket_btn_inpost'] = '';
				}
				if ($_REQUEST['order_btn_own']) {
					$_REQUEST['order_btn'] = '';
				}
				if ($_REQUEST['basket_btn_inpost_additional_txt']) {
					$_REQUEST['basket_btn_inpost_additional_txt'] = trim(strip_tags($_REQUEST['basket_btn_inpost_additional_txt'],'<strong>,<span>,<br>,<a>'));
				}

				$option_main[$value['id']] = $_REQUEST[$value['id']];
				update_option('uwcs_main', $option_main);
					
			}

		}
		
		return true;		

	}
	
	function redirect($location) {

		echo "\n<script type=\"text/javascript\">\n";
		echo "window.location='".$location."';\n";
		echo "</script>\n";

	}
	
}


?>