<?php
include_once "dao.php";

class BO {
    private $dao = null;
    function __construct() {
        $this->dao = new DAO();
    }
    
    public function getUserInfoByUid($uid){
        $list = $this->dao->getUserInfoByUid($uid);
        return $list;
    }
    
    public function getNewCode(){
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
    
    public function getFirst($code, $uid) {
       $code = $this->ifCodeExist($code, $uid);
    }
    
    private function getRandomSix(){
        $randStr = str_shuffle('abcdefghijklmnopqrstuvwxyz1234567890');
        $code = substr($randStr,0,6);
        return $code;
    }
   
}