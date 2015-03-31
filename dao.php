<?php
include_once "ezsql/ez_sql_core.php";
include_once "ezsql/ez_sql_mysql.php";

class DAO {
    private $con = null;
    
    function __construct() {
        $this->con = new ezSQL_mysql('root','root','wine','127.0.0.1');
    }
    
    public function getUserInfoByUid($uid){
        $list = $this->con->get_row("select code,name,score,wine from user where uid='".$uid."'");
        return $list;
    }
	
	public function insertUserInfo($uid,$code,$name){
		$ret = $this->con->query("insert into user values(0,'".$uid."','".$code."','".$name."',0,0)");
        return $ret;
	}
	
	public function updateScore($uid,$score){
		$ret = $this->con->query("update user set `score`=".$score." where uid = '".$uid."'");
		
	}
	
	public function getUserByCode($code){
		$ret = $this->con->get_row("select id,uid,name,score from user where code='".$code."'");
        return $ret;
		
	}
        
    public function ifCodeExist($code, $uid = "") {
        $list = $this->con->get_row("select 1 from user where code='".$code."' and uid !='".$uid."'");
        if(!empty($list)){
            return true;
        }else{
            return false;
        }
    }

}