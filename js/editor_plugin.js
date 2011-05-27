(function() {

	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('UWCartStart');

	tinymce.create('tinymce.plugins.UWCartStart', {

		init : function(edi, url) {
			
			edi.addCommand('mceBasket', function() {
				var content = tinyMCE.activeEditor.selection.getContent({format : 'raw'});
				var newcontent = '[uwcart_basket]';
				
				tinyMCE.activeEditor.selection.setContent(newcontent);
			});
			
			edi.addCommand('mcePrice', function() {
				var content = tinyMCE.activeEditor.selection.getContent({format : 'raw'});
				var newcontent = '[uwcart_price]';
				
				tinyMCE.activeEditor.selection.setContent(newcontent);
			});
			
			edi.addCommand('mceShowcase', function() {
				var content = tinyMCE.activeEditor.selection.getContent({format : 'raw'});
				var newcontent = '[uwcart_showcase]';
				
				tinyMCE.activeEditor.selection.setContent(newcontent);
			});
			
			edi.addButton('uwcart_basket', {
				title : 'Добавить кнопку заказа',
				cmd : 'mceBasket',
				image : url + '/img/ect_insertgood_btn.png'
			});
			
			edi.addButton('uwcart_price', {
				title : 'Вставить цену товара',
				cmd : 'mcePrice',
				image : url + '/img/ect_price_btn.png'
			});
			
			edi.addButton('uwcart_showcase', {
				title : 'Оформить витрину',
				cmd : 'mceShowcase',
				image : url + '/img/ect_showcase_btn.png'
			});
	
	
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'UWCartStart plugin',
				author : 'Igor Ocheretny',
				authorurl : 'http://i-shopmarketing.com',
				infourl : 'http://uwcart.ru',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('UWCartStart', tinymce.plugins.UWCartStart);
})();
