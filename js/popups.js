document.write('<div id="info_fader" style="display:none;position:fixed;width:100%;height:100%;left:0px;top:0px;right:0px;bottom:0;background-color:#000000;z-index:1000;opacity:0.6;-moz-opacity:0.6;-khtml-opacity:0.6;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=60)"></div>');
document.write('<div id="info_block" style="display:none;position:fixed;width:800px;height:450px;top:0px;left:0px;margin:0px;border:0;z-index:1001;"></div>');

function OpenInfoWindow(div_id,info_window_w,info_window_h,imgpath,info_url){

if(typeof info_window_w=='undefined'||info_window_w=='def'){info_window_w=800}
if(typeof info_window_h=='undefined'||info_window_h=='def'){info_window_h=450}

var info_div=document.getElementById('info_block');
info_div.style.width=info_window_w+'px';
info_div.style.height=info_window_h+'px';
info_div.style.left=Math.round(this.getWindowWidth()/2-info_window_w/2)+'px';
info_div.style.top=Math.round(this.getWindowHeight()/2-info_window_h/2)+'px';

var close_button='<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="right"><img src="'+imgpath+'close_w.png" width="25" height="25" alt="close" border="0" onclick="CloseInfoWindow()" style="padding:0;margin:0;cursor:pointer"></td></tr></table>\n';
var DisplayedInfo="";
if(div_id!="iframe"){
	DisplayedInfo=document.getElementById(div_id).innerHTML;
	document.getElementById('info_block').innerHTML='<table style="border:0;" cellpadding="0" cellspacing="0" width="230">'+
	'<tr><td style="width:20px;"></td><td style="width:190px;"></td><td align="right" style="width:20px;"><img src="'+imgpath+'close_w.png" border="0" alt="Закрыть форму" onclick="CloseInfoWindow()" style="padding:0;margin:0;cursor:pointer" /></td></tr>'+
	'<tr><td style="width:20px;"></td><td><div style="width:270px;height:150px;margin:0;padding:0;border-style:none;border-width:0;background-image:url('+imgpath+'ectfrm_bgimg.png);">'+
	'<form action="https://www.ecommtools.com/cgi-bin/account.cgi" method="post" style="margin-top:0;padding:0;border-style:none;" target="_blank">'+
	'<div align="center" style="padding-top:6px;font-family:Tahoma,Verdana,Arial; font-size:10pt;color:White;font-weight:bold;text-shadow:Black 0px 1px 1px">Доступ в аккаунт EcommTools.com</div>'+
	'<div align="center">'+
	'<input type="text" name="user" class="ecommtools_loginform_input" onfocus="if (this.value == \'логин\') this.value=\'\';" onblur="if (this.value == \'\') this.value=\'логин\';" value="логин" />'+
	'<input type="password" name="password"  class="ecommtools_loginform_input" onfocus="if (this.value == \'пароль\') this.value=\'\';" onblur="if (this.value == \'\') this.value=\'пароль\';" value="пароль" />'+
	'<input type="image" src="'+imgpath+'loginform_button.png" style="width:190px;height:25px;margin-top:10px;" /></div>'+
	'<input type="hidden" name="action" value="login" />'+
	'</form></div></td><td style="width:20px;"></td></tr>'+
	'<tr><td colspan="2" align="center"><br /><a style="color:#d5d5d5;text-decoration:none;font-weight:bold;" href="http://www.ecommtools.com/register.html" target="_blank">Зарегистрировать Аккаунт</a></td><td style="width:20px;"></td></tr>'+
	'</table>';
}
else{
	var iframe_data='<iframe src="'+info_url+'"style="width:100%;height:100%;border:0;" marginwidth="0" marginheight="0" frameborder="0" align="left">error</iframe>';
	document.getElementById('info_block').innerHTML=close_button+'<div id="information" style="width:100%;height:100%;overflow:auto;">'+iframe_data+'</div>';
}

document.getElementById('info_fader').style.display="block";
document.getElementById('info_block').style.display="block";
}

function CloseInfoWindow(){
document.getElementById('info_block').innerHTML="";
document.getElementById('info_block').style.display="none";
document.getElementById('info_fader').style.display="none";
}

function getWindowWidth(){
return document.compatMode=='CSS1Compat'&&!window.opera?document.documentElement.clientWidth:document.body.clientWidth
}

function getWindowHeight(){
return window.innerHeight||(document.documentElement && document.documentElement.clientHeight)||(document.body.clientHeight)
}