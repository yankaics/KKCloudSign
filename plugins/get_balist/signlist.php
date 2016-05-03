<?php
if (! defined ( 'IN_KKFRAME' ))	exit ( 'Access Denied!' );
echo '<h2><b style="color:red;">新</b>签到记录列表</h2><p style="color:#757575;font-size:12px">当前插件版本：1.0 | 更新日期：2015-04-18 | Designed By <a href="http://tieba.baidu.com/home/main?id=36cebfecc0d6cac7d3cecfb7d5e6dad05c5c&fr=userbar" target="_blank">@快乐是游戏真谛</a></p>';
echo '<style>#menu_liked_tieba,#menu_sign_log{display:none;}</style>';
?>
<p id="sign-stat"></p>
<p>
 <a id="get_balog_before"   href="#" class="btn submit " style="display: none;" onclick="return false;">前一天</a>
 <a id="get_balog_up"       href="#" class="btn submit" style="display: none;" onclick="return false;">上一页</a>
 <a id="get_balog_today"    href="#" class="btn submit " style="display: none;" onclick="return false;">今天</a>
 <a id="get_balog_down"     href="#" class="btn submit" style="display: none;" onclick="return false;">下一页</a>
 <a id="get_balog_after"    href="#" class="btn submit" style="display: none;" onclick="return false;">后一天</a>
</p>
<table>
<thead><tr><td style="width: 40px">#</td><td>贴吧</td><td class="mobile_min">状态</td><td class="mobile_min">经验</td></tr></thead>
<tbody></tbody>
</table>
<input type="hidden" id="getlog_current_page" value="1"/>
<input type="hidden" id="getlog_current_date" value="<?php echo date('Ymd')?>"/>
<input type="hidden" id="getlog_today" value="<?php echo date('Y-m-d')?>"/>