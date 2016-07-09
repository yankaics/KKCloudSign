<?php 
require_once '../../system/common.inc.php';



error_reporting(0);
if(!$uid){
	$re= array('no'=>'-3','msg'=>'请先登录');
}else{

    function new_get_cookie($uid){
	   static $cookie = array();
	   if($cookie[$uid]) return $cookie[$uid];
	   $cookie[$uid] = DB::result_first("SELECT cookie FROM member_setting WHERE uid='{$uid}'");
	   $cookie[$uid] = strrev(str_rot13(pack('H*', $cookie[$uid])));
	   return $cookie[$uid];
    }

    $re= array();
    $pn = floatval($_GET['pn']?$_GET['pn']:$_POST['pn']);
    $pn = $pn&&$pn>0? $pn :1;
    $data = $_GET['data']?$_GET['data']:$_POST['data'];
    $action = $_GET['action']?$_GET['action']:$_POST['action'];
    $add_only = $_GET['add_only']?$_GET['add_only']:$_POST['add_only'];
    $add_only = $add_only==''||$add_only=='0'||$add_only=='false'?'0':'1';
    
    if($action=='get'){
        $date = date('Ymd', TIMESTAMP + 900);
        $cookie = new_get_cookie($uid);
        if(!$cookie){
            $re= array('no'=>'-2','msg'=>'请先绑定 Cookie 信息再更新');
        }
        $ch = curl_init('http://tieba.baidu.com/i/sys/user_json');
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        $res = @json_decode(iconv("GB2312","UTF-8//IGNORE",$res), true);
        $userid=$res&&$res["creator"]&&$res["creator"]["id"]?$res["creator"]["id"]:0;
    
        if(!$userid){
            $re= array('no'=>'-1','msg'=>'Cookie 可能失效了！');
        }else{
            $kw_name = array();
            $retry = 0;
            while (true){
                $ch = curl_init('http://c.tieba.baidu.com/c/f/forum/like');
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'User-Agent: Mozilla/5.0 (SymbianOS/9.3; Series60/3.2 NokiaE72-1/021.021; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/525 (KHTML, like Gecko) Version/3.0 BrowserNG/7.1.16352'));
                curl_setopt($ch, CURLOPT_COOKIE, $cookie);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                $array = array(
                    '_client_id' => 'wappc_' . time() . '_' . '345',
                    '_client_type' => 2,
                    '_client_version' => '6.5.8',
                    '_phone_imei' => '357143042411618',
                    'from' => 'baidu_appstore',
                    'is_guest' => 1,
                    'model' => 'H60-L01',
                    'page_no' => $pn,
                    'page_size' => 200,
                    'timestamp' => time(). '516',
                    'uid' => $userid,
                );
                $sign_str = '';
                foreach($array as $k=>$v) $sign_str .= $k.'='.$v;
                $sign = strtoupper(md5($sign_str.'tiebaclient!!!'));
                $array['sign'] = $sign;
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array));
                $ba_json = curl_exec($ch);
                curl_close($ch);
                $res = @json_decode(iconv("GB2312","UTF-8//IGNORE",$ba_json), true);
                $forum_list=$res&&$res['forum_list']?$res['forum_list']:array();
                if($forum_list['non-gconforum']) foreach ($forum_list['non-gconforum'] as $value) {
                    $kw_name[] = array(
                        'name' => $value['name'],
                        'uname' => urlencode($value['name']),
                        'fid' => $value['id'],
                    );
                    $count++;
                }
                if($forum_list['gconforum']) foreach ($forum_list['gconforum'] as $value) {
                    $kw_name[] = array(
                        'name' => $value['name'],
                        'uname' => urlencode($value['name']),
                        'fid' => $value['id'],
                    );
                    $count++;
                }
                if(count($forum_list)&&!$forum_list['gconforum']&&!$forum_list['non-gconforum']) foreach ($forum_list as $value) {
                    $kw_name[] = array(
                        'name' => $value['name'],
                        'uname' => urlencode($value['name']),
                        'fid' => $value['id'],
                    );
                    $count++;
                }
                if (count($forum_list)==0&&$retry <= 4) {
                    $retry++;
                }else{
                    break;
                }
            }
            $re=(array('no'=>0,'pn'=>$pn,'msg'=>'成功','more'=>($res['has_more']=='1'?1:0),'data'=>$kw_name));
        }
    }elseif($action=='set'&&$data){
        $liked_tieba=($data);
        $date = date('Ymd', TIMESTAMP + 900);
        $insert = 0;
        $count = count($liked_tieba);
        $re["msg"]="<p>您共计关注了 {$count} 个贴吧，</p>";
        if($limit = getSetting('max_tieba')){
            if($limit < $count){
                $re["msg"].="<p>管理员限制了每位用户最多关注 {$limit} 个贴吧</p>";
            }
        }
        $my_tieba = array();
        $query = DB::query("SELECT name, fid, tid FROM my_tieba WHERE uid='{$uid}'");
        while($r = DB::fetch($query)) {
            $my_tieba[$r['name']] = $r;
        }
        foreach($liked_tieba as $tieba){
            if($my_tieba[$tieba['name']]){
                unset($my_tieba[$tieba['name']]);
                if(!$my_tieba[$tieba['name']]['fid']) DB::update('my_tieba', array(
                     'fid' => $tieba['fid'],
                    ), array(
                        'uid' => $uid,
                        'name' => $tieba['name'],
                    ), true);
                continue;
            }else{
                DB::insert('my_tieba', array(
                    'uid' => $uid,
                    'fid' => $tieba['fid'],
                    'name' => $tieba['name'],
                    'unicode_name' => $tieba['uname'],
                    ), false, true, true);
                $insert++;
            }
        }
        DB::query("INSERT IGNORE INTO sign_log (tid, uid, `date`) SELECT tid, uid, '{$date}' FROM my_tieba");
        if($my_tieba && $add_only=='0'){
            $tieba_ids = array();
            foreach($my_tieba as $tieba){
                $tieba_ids[] = $tieba['tid'];
            }
            $str = "'".implode("', '", $tieba_ids)."'";
            DB::query("DELETE FROM my_tieba WHERE uid='{$uid}' AND tid IN ({$str})");
            DB::query("DELETE FROM sign_log WHERE uid='{$uid}' AND tid IN ({$str})");
        }
        $re["insert"]=$insert;
        $re["no"]='0';
    }elseif($action=='log'){
        $re["pn"]=$pn;
        $re["more"]='0';
        $pn=($pn-1)*200;
		$date = date('Ymd');
		$re['date'] = date('Y-m-d');
		if($_GET['date']){
			$date = intval($_GET['date']);
			$re['date'] = substr($date, 0, 4).'-'.substr($date, 4, 2).'-'.substr($date, 6, 2);
		}
		$re['log'] = array();
		$query = DB::query("SELECT * FROM sign_log l LEFT JOIN my_tieba t ON t.tid=l.tid WHERE l.uid='{$uid}' AND l.date='{$date}' ORDER BY l.tid ASC LIMIT {$pn} , 201");
		while($result = DB::fetch($query)){
            if(count($re["log"])>199){
                $re["more"]='1';break;
            }
			$re['log'][] = $result;
		}
		$re['count'] = count($re['log']);
		$re['before_date'] = DB::result_first("SELECT date FROM sign_log WHERE uid='{$uid}' AND date<'{$date}' ORDER BY date DESC LIMIT 0,1");
		$re['after_date'] = DB::result_first("SELECT date FROM sign_log WHERE uid='{$uid}' AND date>'{$date}' ORDER BY date ASC LIMIT 0,1");
        
    }elseif($action=='list'){
        $re["pn"]=$pn;
        $pn=($pn-1)*200;
        $re["data"]=array();
        $re["more"]='0';
		$query = DB::query("SELECT * FROM my_tieba WHERE uid='{$uid}' ORDER BY `tid` ASC LIMIT {$pn} , 201");
		while($result = DB::fetch($query)){
            if(count($re["data"])>199){
                $re["more"]='1';break;
            }
			$re["data"][] = $result;
        }
    }else{
        $re=array('no'=>'2','msg'=>'我能为你做些啥？');
    }
}

echo json_encode($re);
?>