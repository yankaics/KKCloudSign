<?php
if (! defined ( 'IN_KKFRAME' ))	exit ( 'Access Denied!' );
echo '<h2><b style="color:red;">新</b>贴吧关注列表</h2><p style="color:#757575;font-size:12px">当前插件版本：1.0 | 更新日期：2015-04-18 | Designed By <a href="http://tieba.baidu.com/home/main?id=36cebfecc0d6cac7d3cecfb7d5e6dad05c5c&fr=userbar" target="_blank">@快乐是游戏真谛</a></p>';
?>
<p>通过客户端接口获取贴吧列表。啥也不说了，帮忙测试吧！</p>
<input type="hidden" id="getba_current_page" value="1"/>
<label><input type="checkbox" id="getba_clear" checked="true"/>刷新时清空原有列表 </label> 
<a id="get_balist" href="#" class="btn red"	onclick="javascript:return false;">点我重新获取列表</a>
 <a id="get_balist_up" href="#" class="btn submit" style="display: none;" onclick="javascript:return false;">上一页</a>
 <a id="get_balist_down" href="#" class="btn submit" style="display: none;" onclick="javascript:return false;">下一页</a>
<table>
<thead><tr><td style="width: 40px">#</td><td>贴吧</td><td style="width: 65px">忽略签到</td></tr></thead>
<tbody></tbody>
</table>