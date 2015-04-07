<?php
include_once "dao.php";

class BO {
    private $dao = null;
    function __construct() {
        $this->dao = new DAO();
    }
    
    public function getAward($uid,$address,$contact,$phone){
        $userInfo = $this->dao->getUserInfoByUid($uid);
        if(!empty($userInfo)){
            $userInfo = $this->object_array($userInfo);
            $score = (int)$userInfo["score"];
            $wine = (int)$userInfo["wine"];
            $id = (int)$userInfo["id"];
            if($score>=120){
                $this->dao->updateScore($uid,$score-120);
                $this->dao->updateWine($uid,$wine+1);
                $this->dao->addAddress($id,$address,$contact,$phone);
                return true;
            }            
   
            return false;
        }else{
            return false;
        }
        
    }
    
    public function close(){
        $this->dao->close();
    }
    
    public function getUserInfoByUid($uid,$name){
        $ret = $this->dao->getUserInfoByUid($uid);
		if(empty($ret)){
			$code = $this->getNewCode($uid);
			$name = base64_decode($name);
			$flag = $this->dao->insertUserInfo($uid,$code,$name);
			if($flag){
				$ret = $this->dao->getUserInfoByUid($uid);
                $ret = $this->object_array($ret);
			}else{
				$ret = false;
			}
		}else{
            $ret = $this->object_array($ret);
            $id = $ret["id"];
            $friend = $this->dao->getFriendById($id);
            if(!empty($friend)){
                $friend = $this->object_array($friend);
                $ret["friend"] = $friend;
            }
        };
        if(!empty($ret)){
            unset($ret["id"]);
        }
        return $ret;
    }
	
	public function getFirst($code, $uid) {
       $codeExist = $this->dao->ifCodeExist($code, $uid);
	   if(!$codeExist){
		   return false;
	   }
	   
	   $userInfo = $this->dao->getUserInfoByUid($uid);
	   $userInfo = $this->object_array($userInfo);
	   $wine = (int)$userInfo["wine"];
	   $score = (int)$userInfo["score"];
	   if($wine ==0 && $score ==0){
		   $otherUser = $this->dao->getUserByCode($code);
		   $otherUser = $this->object_array($otherUser);
		   $this->dao->updateScore($uid,50);
		   $score = (int)$otherUser["score"];
		   $this->dao->updateScore($otherUser["uid"],$score+10);
           $this->dao->addFriend($otherUser["id"],$userInfo["id"]);
		   $ret = array(
		       "fname"=>$otherUser["name"],
			   "code"=>$userInfo["code"]
			   );
		   return $ret;
	   }
	   
	   return false;
    }
    
    public function getNewCode($uid){
        $retryTimes = 3;
        $code = null;
        
        while($retryTimes--){
            $code = $this->getRandomSix();
            $flag = $this->dao->ifCodeExist($code);
            if(!$flag){
                return $code;
            }
        }

        return null;
    }
    
    private function getRandomSix(){
        $randStr = str_shuffle('abcdefghijklmnopqrstuvwxyz1234567890');
        $code = substr($randStr,0,6);
        return $code;
    }
	
	private function object_array($array) {  
		if(is_object($array)) {  
			$array = (array)$array;  
		} if(is_array($array)) {  
			foreach($array as $key=>$value) {  
				$array[$key] = $this->object_array($value);  
			}  
		}  
		return $array;  
	}
   
}