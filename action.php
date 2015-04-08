<?php
include_once "bo.php";
include_once "aes.php";
include_once "keys.php";

$action = $_POST["action"];
$auth = $_POST["auth"];
$name = clear_xss($_POST["name"]);
$invCode = clear_xss($_POST["invcode"]);
$address = clear_xss($_POST["address"]);
$contact = clear_xss($_POST["contact"]);
$phone = clear_xss($_POST["phone"]);
$shareurl = $_POST["shareurl"];

header('Content-Type: application/json');

$uid = getUidFormAuth($auth,$PRIVATE_KEYS);
if(empty($uid)){
    error_msg("auth error");
    return;
}

//加密
//$a  = Security::encrypt("drwine000088889999","dsaddLOhkiHKhkHkhkhkdhsO");
//echo($a);

$BO = new BO();

switch($action){
    case 'getInfo':
        $ret = $BO->getUserInfoByUid($uid,$name);
		if(!empty($ret)){
			suc_msg($ret);
		}else{
			error_msg("该用户无效！");
		}
        
        break;
    case 'getFirst':
        $ret = $BO->getFirst($invCode,$uid);
		if(!empty($ret)){
			suc_msg($ret);
		}else{
			error_msg("领取失败！");
		};
        
        break;
     case 'getAward':
        $ret = $BO->getAward($uid,$address,$contact,$phone);
        if(!empty($ret)){
			suc_msg($ret);
		}else{
			error_msg("领取奖品失败！");
		};
        break;
     case 'getWxConfig':
        $ret = $BO->getWxConfig($shareurl);
        if(!empty($ret)){
			suc_msg($ret);
		}else{
			error_msg("获取ticket失败");
		};
        break;
}

$BO->close();

function suc_msg($data = "",$rmsg = ""){
    $arr = array(
        "rc" => "0",
        "rmsg" => $rmsg,
        "data" => $data
    );
    echo(json_encode($arr));
}

function error_msg($rmsg = ""){
    $arr = array(
        "rc" => "1",
        "rmsg" => $rmsg
    );
    echo(json_encode($arr));
}

function getUidFormAuth($str,$keys){
    if(strlen($str)>6){
        try{
            $aes = substr($str,0,5) . substr($str,6);
            $keyIndex = substr($str,5,1);    
            if(array_key_exists($keyIndex, $keys)){
                $key = $keys[$keyIndex];
                $uid = Security::decrypt($aes,$key);
                if(preg_match("/^drwine[0-9]{12}/",$uid)){
                    return $uid;
                }
                false;
            }
            
            return false;
        }catch(Exception $e){
            return $e;
        }        
    }else{
        return false;
    }
}

function clear_xss($str){
    if (isset($str) && !empty($str)){
       $str = strip_tags($str);   //过滤html标签
       $str = htmlspecialchars($str);   //将字符内容转化为html实体
       $str = addslashes($str);
       return $str;
    }
}



