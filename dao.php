<?php
include_once "ezsql/ez_sql_core.php";
include_once "ezsql/ez_sql_mysql.php";

class DAO {
    private $con = null;
    //$this->con = new ezSQL_mysql('root','d7CD2E8d1522!','wine7_1','127.0.0.1');
    function __construct() {
        $this->con = new ezSQL_mysql('root','root','wine','127.0.0.1');
    }
    
    public function getUserInfoByUid($uid){
        $list = $this->con->get_row("select id,code,name,score,wine from user where uid='".$uid."'");
        return $list;
    }
    
    public function getFriendById($id){
        $ret = $this->con->get_results("select user.name from friend left join user on friend.fid = user.id where friend.id = ".$id."");
        return $ret;
    }
	
	public function insertUserInfo($uid,$code,$name){
		$ret = $this->con->query("insert into user values(0,'".$uid."','".$code."','".$name."',0,0)");
        return $ret;
	}
    
    public function addAddress($id,$address,$contact,$phone){
		$ret = $this->con->query("insert into address values('".$id."','".$address."','".$contact."','".$phone."')");
        return $ret;
	}
	
	public function updateScore($uid,$score){
		$ret = $this->con->query("update user set `score`=".$score." where uid = '".$uid."'");
		return $ret;
	}
    
    public function updateWine($uid,$wine){
		$ret = $this->con->query("update user set `wine`=".$wine." where uid = '".$uid."'");
		return $ret;
	}
	
	public function getUserByCode($code){
		$ret = $this->con->get_row("select id,uid,name,score from user where code='".$code."'");
        return $ret;
		
	}
    
    public function addFriend($id,$fid){
        $ret = $this->con->query("insert into friend values(".$id.",".$fid.")");
        
    }
        
    public function ifCodeExist($code, $uid = "") {
        $list = $this->con->get_row("select 1 from user where code='".$code."' and uid !='".$uid."'");
        if(!empty($list)){
            return true;
        }else{
            return false;
        }
    }
    
    public function close(){
        $this->con->disconnect();
    }

}