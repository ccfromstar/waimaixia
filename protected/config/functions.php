<?php
function getUserRole($uid)
{
    $roles = Rights::getAssignedRoles($uid);
    $tmp = array();
    foreach($roles as $role)
    {
        $tmp[] = $role->description;
    }
    return implode('、',$tmp);
}

function cutstr($string, $length, $start = 0) {
    $len = mb_strlen($string,'utf-8');
    $re = mb_substr($string,$start,$length,'utf-8');
    if($len > $length)
        $re .= '...';
    return $re;
}

function isAdmin($uid = 0) {
    if (!$uid)
        return Yii::app()->user->checkAccess('Admin');
    else
        return Yii::app()->getAuthManager()->checkAccess('Admin', $uid);
}

function http_get($sUrl, $aGetParam)
{
    $oCurl = curl_init();
    if (stripos($sUrl, "https://") !== FALSE) {
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    $aGet = array();
    foreach ($aGetParam as $key => $val) {
        $aGet[] = $key . "=" . urlencode($val);
    }
    curl_setopt($oCurl, CURLOPT_URL, $sUrl . "?" . join("&", $aGet));
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
    $sContent = curl_exec($oCurl);
    $aStatus = curl_getinfo($oCurl);
    curl_close($oCurl);
    if (intval($aStatus["http_code"]) == 200) {
        return $sContent;
    }
    return '';
}

/*判断是否是手机登录的*/
function is_mobile_request()   
{   
  $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';   
  $mobile_browser = '0';   
  if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))   
    $mobile_browser++;   
  if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))   
    $mobile_browser++;   
  if(isset($_SERVER['HTTP_X_WAP_PROFILE']))   
    $mobile_browser++;   
  if(isset($_SERVER['HTTP_PROFILE']))   
    $mobile_browser++;   
  $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));   
  $mobile_agents = array(   
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',   
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',   
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',   
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',   
        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',   
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',   
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',   
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',   
        'wapr','webc','winw','winw','xda','xda-'  
        );   
  if(in_array($mobile_ua, $mobile_agents))   
    $mobile_browser++;   
  if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)   
    $mobile_browser++;   
  // Pre-final check to reset everything if the user is on Windows   
  if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)   
    $mobile_browser=0;   
  // But WP7 is also Windows, with a slightly different characteristic   
  if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)   
    $mobile_browser++;   
  if($mobile_browser>0)   
    return "1";   
  else 
    return "0"; 
}

function sms($phone, $msg, $operid = 0)
{
    $account = 'dagou';
    $password = '123456';
    $url = 'http://sms.eloone.com/ylSend.do?';
    $omsg = $msg;
    $msg = urlencode(iconv('utf-8', 'gb2312', $msg));
    $url .= 'uid=' . $account . '&pwd=' . $password;
    $url .= '&rev=' . $phone . ',&msg=' . $msg . '&sdt=&snd=1011';
    $handler = curl_init($url);
    curl_setopt($handler, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($handler);
    curl_close($handler);
    /**$model = new Mobilelog();
    $model->mobile = $phone;
    $model->msg = $omsg;
    $model->result = $result;
    $model->addtime = time();
    if (!$operid)
        $model->operid = Yii::app()->user->getId();
    else
        $model->operid = intval($operid);
    $model->save();**/
    return $result;
}

//json_encode不兼容JSON_UNESCAPED_UNICODE的解决方案
function json_encode_ex($value){
	if(version_compare(PHP_VERSION, '5.4.0','<')){
		$str = json_encode($value);
		$str = preg_replace_callback("#\\\u([0-9a-f]{4})#i", function($matchs){
			return iconv('UCS-2BE','UTF-8',pack('H4',$matchs[1]));
		}, $str);

		return $str;
	}else{
		return json_encode($value,JSON_UNESCAPED_UNICODE);
	}
}

function Post($curlPost,$url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
		$return_str = curl_exec($curl);
		curl_close($curl);
		return $return_str;
}
function xml_to_array($xml){
	$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
	if(preg_match_all($reg, $xml, $matches)){
		$count = count($matches[0]);
		for($i = 0; $i < $count; $i++){
		$subxml= $matches[2][$i];
		$key = $matches[1][$i];
			if(preg_match( $reg, $subxml )){
				$arr[$key] = xml_to_array( $subxml );
			}else{
				$arr[$key] = $subxml;
			}
		}
	}
	return $arr;
}