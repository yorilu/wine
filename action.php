<?php
include_once "bo.php";
include_once "aes.php";
include_once "keys.php";

$action = $_POST["action"];
$auth = $_POST["auth"];
$name = $_POST["name"];
$invCode = $_POST["invcode"];
$err = array("rc"=>"1","msg"=>"sss");
$BO = new BO();

header('Content-Type: application/json');

$uid = getUidFormAuth($auth,$PRIVATE_KEYS);
if(empty($uid)){
    error_msg("auth error");
    return;
}

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
}

function suc_msg($data = "",$rmsg = ""){
    $arr = [
        "rc" => "0",
        "rmsg" => $rmsg,
        "data" => $data
    ];
    echo(json_encode($arr));
}

function error_msg($rmsg = ""){
    $arr = [
        "rc" => "1",
        "rmsg" => $rmsg
    ];
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




