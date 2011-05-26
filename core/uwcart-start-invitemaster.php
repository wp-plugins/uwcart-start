<?php
class UWPCInviteMaster extends UWPCstart {

	
	function is_im_dir() {
		
		clearstatcache();
		$im_file = str_replace('wp-admin','wp-content',getcwd()).'/plugins/invitemaster/invitemaster.php';
		
		if (file_exists($im_file)) {
			return true;
		}
		else return false;
		
	}
	
	function is_im_activated() {
		
		$active_plugins = get_option('active_plugins');
		if (in_array('invitemaster/invitemaster.php', $active_plugins)) $is_im = TRUE;
		else $is_im = FALSE;
		
		return $is_im;
		
	}
	
	function show_about_im() {

	?>
		
		<div class="wrap">
		
		<h2>Сервис дружеских рекомендаций &laquo;InviteMaster&raquo;<?php if ($this->is_im_activated() && $this->is_im_dir() == true) : echo " активирован"; elseif(!$this->is_im_activated() && $this->is_im_dir() == true) : echo " установлен"; endif; ?></h2>
		
		<table style="width:100%;margin-top:10px;">
		<tr>
		<td width="60%" valign="top">
		
		<div class="inside" style="padding:10px 20px;line-heght:17px;">
		<?php
		if ($this->is_im_dir() == true) :
		?>
			<table style="width:100%;margin-top:10px;">
			<tr class='active second'>	
				<td class='desc'>Автор: <a href="http://invitemaster.ru" title="Перейти на страницу автора">InviteMaster.ru</a></td>
			</tr>
			<tr class='active second'>
				<td class='plugin-title'><div class="row-actions-visible" style="margin-top:20px;"><a href="http://invitemaster.ru" target="_blank" title="Перейти на страницу плагина">Перейти на страницу плагина</a> | 
				<?php if (get_option('invitemaster_options')) : ?>
				<span class='deactivate'><a href="plugins.php" title="Деактивировать плагин">Деактивировать</a> | </span>
				<?php else : ?>
				<span class='activate'><a href="plugins.php" title="Активировать плагин" class="edit">Активировать</a> | </span>
				<?php endif; ?>
				</div></td>
			</tr>
			</table>
		<?php
		else :
		?>
		
		<p style="font-size:90%;text-align:justify;line-height:20px;">
		Сервис дружеских рекомендаций <a href="http://www.invitemaster.ru" target="_blank">InviteMaster.ru</a> это невероятно эффективный способ увеличить число посетителей вашего сайта, а следовательно - подписчиков, партнеров и клиентов!
		</p>

		<p style="font-size:90%;line-height:20px;text-align:justify;">
		<em>InviteMaster</em> - это новое поколение программ "расскажи другу" со множеством возможностей, реализующий главные принципы "вирусного" маркетинга:
		</p>
		<ul style="font-size:90%;text-indent:20px;">
		<li><em>* Предельно простое донесение сообщения людям;</em></li>
		<li><em>* Предоставление вознаграждения за рекомендацию.</em></li>
		</ul>
		<br />
		<p style="font-size:90%;text-align:justify;">
		Теперь вы легко сможете:
		</p>
		<ul style="font-size:90%;text-indent:20px;">
		<li><em>* Получать новый трафик <strong><font color="#FF0000">СОВЕРШЕННО БЕСПЛАТНО</font></strong>, в то время как другие платят за это!</em></li>
		<li><em>* Многократно увеличить число подписчиков и партнеров, и как следствие - объем продаж.</em></li>
		</ul>
		<br />
		<p style="font-size:90%;text-align:justify;">
		И для этого вам <span style="color:#ff0000;font-weight:bold;"><em>НЕ ПОТРЕБУЕТСЯ</em></span>:
		</p>
		<ul style="font-size:90%;text-indent:20px;">
		<li><em>* платить за улучшение рекламного текста;</em></li>
		<li><em>* платить за семинары и консультации по продвижению сайтов;</em></li>
		<li><em>* оплачивать программы обмена трафиком;</em></li>
		<li><em>* и самое главное - вам не надо тратить лишние деньги на рекламу.</em></li>
		</ul>
		<br>

		<div>
		<h2>Установить InviteMaster</h2>
		<form method="post" action="<?php echo get_option('siteurl') . '/wp-admin/plugin-install.php?tab=search'; ?>" accept-charset="utf-8">
		<input type="hidden" name="type" value="term">
		<input type="hidden" name="s" value="invitemaster">
		<input type="submit" value="установить" class="button" />
		</form>
		</div>
		</div>
		<?php endif; ?>
		</td>
		<td width="40%" valign="top">&nbsp;</td>
		</tr>
		</table>
		</div>
		
	<?php
	}
}

?>