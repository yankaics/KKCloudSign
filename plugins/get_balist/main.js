$("#menu #menu_sign_log").remove();
$("#menu #menu_liked_tieba").remove();
function load_liked_tieba(){
	$("#menu_get_balist-index").click()
}
function load_sign_log(){
	$("#menu_get_balist-signlog").click()
}

function com_onload(){
	$("#menu #menu_get_balist-signlog").click();
}
$(document).ready(function() {
	if(com_onload&&(location.hash==""||location.hash=="#liked_tieba"||location.hash=="#sign_log")) com_onload();
});

$("#menu_get_balist-signlog").click(function (){new_get_signlog_load();});
$("#get_balog_before").click(function (){new_get_signlog_load(0,this.href);});
$("#get_balog_after").click(function (){new_get_signlog_load(0,this.href);});
$("#get_balog_today").click(function (){new_get_signlog_load(0);});
$("#get_balog_up").click(function (){new_get_signlog_load(-1,$("#getlog_current_date").val());});
$("#get_balog_down").click(function (){new_get_signlog_load(1,$("#getlog_current_date").val());});

function new_get_signlog_load(pn,date){
	pn=$("#getlog_current_page").val()*1+(parseInt(pn)==pn?pn:0);
	date=date&&date.match(/#\d+/g)?("&date="+date.match(/#\d+/g)[0].match(/\d+/g)[0]):"";
	showloading();
	$.getJSON("plugins/get_balist/ajax.php?action=log&pn="+pn+date, function(result){
		if(!result) return;
		$("#getlog_current_page").val(result.pn);
		$("#getlog_current_date").val(result.date.replace(/-/g,""));
		if(result.count == 0 && !guide_viewed){
			$('#menu_guide').click();
			return;
		}
		new_show_sign_log(result);
	}).fail(function() { createWindow().setTitle('系统错误').setContent('发生未知错误: 无法获取签到报告').addButton('确定', function(){ location.reload(); }).append(); }).always(function(){ hideloading(); });
}
function new_show_sign_log(result) {
	stat[0] = stat[1] = stat[2] = stat[3] = stat[4] = 0;
	if (!result || result.count == 0) return;
	$('#content-get_balist-signlog table tbody').html('');
	$('#content-get_balist-signlog h2').html(' <b style="color:red;">新</b>'+result.date + '签到记录');
	$.each(result.log, function(i, field) {
		$("#content-get_balist-signlog table tbody").append("<tr><td>" + (i + 1) + "</td><td><a href=\"http://tieba.baidu.com/f?kw=" + field.unicode_name + "\" target=\"_blank\">" + field.name + "</a></td><td>" + _status(field.status) + "</td><td>" + _exp(field.exp) + "</td></tr>");
	});
	var result_text = "";
	result_text += "本页共计 " + (stat[0] + stat[1] + stat[2] + stat[3] + stat[4]) + " 个贴吧";
	result_text += ", 成功签到 " + (stat[4]) + " 个贴吧";
	if (stat[2]) result_text += ", 有 " + (stat[2]) + " 个贴吧尚未签到";
	if (stat[0]) result_text += ", 已跳过 " + (stat[0]) + " 个贴吧";
	if (stat[3]) result_text += ", " + (stat[3]) + " 个贴吧正在等待重试";
	if (stat[1]) result_text += ", " + (stat[1]) + " 个贴吧无法签到, <a href=\"index.php?action=reset_failure&formhash=" + formhash + "\" onclick=\"return msg_redirect_action(this.href)\">点此重置无法签到的贴吧</a>";
	$('#content-get_balist-signlog #sign-stat').html(result_text);

	if (result.before_date){$("#get_balog_before").show().attr("href","#"+result.before_date);}else{$("#get_balog_before").hide();}
	if (result.after_date){$("#get_balog_after").show().attr("href","#"+result.after_date);}else{$("#get_balog_after").hide();}
	if ($('#getlog_today').val()!=result.date){$("#get_balog_today").show();}else{$("#get_balog_today").hide();}
	if(result.pn>1){$("#get_balog_up").show();}else{$("#get_balog_up").hide();}
	if(result.more==1){$("#get_balog_down").show();}else{$("#get_balog_down").hide();}
}




$("#menu_get_balist-index").click(function (){new_get_balist_load(0);});
$("#get_balist_down").click(function (){new_get_balist_load(1);});
$("#get_balist_up").click(function (){new_get_balist_load(-1);});
function new_get_balist_load(pn){
	pn=$("#getba_current_page").val()*1+(parseInt(pn)==pn?pn:0);
	showloading();
	$.getJSON("plugins/get_balist/ajax.php?action=list&pn="+pn, function(result){
		if(!result) return;
		$("#getba_current_page").val(result.pn);
		if(result.pn>1){$("#get_balist_up").show();}else{$("#get_balist_up").hide();}
		if(result.more==1){$("#get_balist_down").show();}else{$("#get_balist_down").hide();}
		result=result.data;
		$('#content-get_balist-index table tbody').html('');
			var tieba_name = new Array;
			var tieba_uname = new Array;
			$.each(result, function(i, field){
				if(typeof localStorage != 'undefined'){
					tieba_name.push(field.name);
					tieba_uname.push(field.unicode_name);
				}
				$("#content-get_balist-index table tbody").append("<tr><td>"+(i+1)+"</td><td><a href=\"http://tieba.baidu.com/f?kw="+field.unicode_name+"\" target=\"_blank\">"+field.name+"</a></td><td><input type=\"checkbox\" value=\""+field.tid+"\""+(field.skiped=='1' ? ' checked' : '')+" class=\"skip_sign\" /></td></tr>");
			});
		$('#content-get_balist-index .skip_sign').click(function(){
			showloading();
			this.disabled = 'disabled';
			$.getJSON('index.php?action=skip_tieba&format=json&tid='+this.value+'&formhash='+formhash, function(result){ new_get_balist_load(); }).fail(function() { hideloading(); createWindow().setTitle('系统错误').setContent('发生未知错误: 无法修改当前贴吧设置').addCloseButton('确定').append(); });
			
		});
		if(typeof localStorage != 'undefined'){
			localStorage['tieba_name'] = tieba_name.join('||');
			localStorage['tieba_uname'] = tieba_uname.join('||');
		}
	}).fail(function() { createWindow().setTitle('系统错误').setContent('发生未知错误: 无法获取设置').addButton('确定', function(){ location.reload(); }).append(); }).always(function(){ hideloading(); });
}

$("#get_balist").click(function (){
	var pn=1,msg,tmp;
	var win=createWindow().setTitle('列表获取').setContent('<div align="center"><img src="./template/default/style/loading.gif" /><span id="msg">载入中...</span></div>').addButton('停止并刷新', function(){location.reload();});
	win.append();tmp=win.obj.getElementsByTagName("span");
	for(var i=0;i<tmp.length;i++){if(tmp[i].id=="msg"){msg=tmp[i];break;}};msg=msg?msg:{};tmp=0;
	var add_only=document.getElementById("getba_clear");
	add_only=add_only&&add_only.checked?0:1;
	function getba(){
		msg.innerHTML="正在获取第【"+pn+"】页，当前新增贴吧【"+tmp+"】个";
		$.ajax({url:"plugins/get_balist/ajax.php",data:'action=get&pn='+pn,type:"post",complete:function(s){
			if(s.statusText=="OK"){
				s=JSON.parse(s.responseText.replace(/<script.+?<\/script>/g,""));
				if(s.no==0){
					pn=s.pn;
					s.action="set";s.add_only=add_only++;
					if(s.data.length) $.ajax({url:"plugins/get_balist/ajax.php",type:"post",data:s,complete:function(d){
						if(d.statusText=="OK"){
							d=JSON.parse(d.responseText.replace(/<script.+?<\/script>/g,""));
							if(d.no==0){
								pn++;tmp+=d.insert;
								if(s.more==0){
									win.close();
									createWindow().setTitle('列表获取').setContent('<div align="center">获取完毕，新增贴吧【 '+tmp+' 】个</div>').addCloseButton("确定").append();
								}else if(s.more==1){
									getba();
								}else{
									msg.innerHTML="获取出错，请重试！";
								}
							}
						}else{getba();}
					}});
				}else{getba();}
			}else{getba();}
		}});
	}
	getba();
});