<?php
include_once "ezsql/ez_sql_core.php";
include_once "ezsql/ez_sql_mysql.php";

class DAO {
    private $con = null;
    
    function __construct() {
        $this->con = new ezSQL_mysql('root','root','wine','127.0.0.1');
    }
    
    public function getUserInfoByUid($uid){
        $list = $this->con->get_results("select code,name,scroe,wine from USER where uid='".$uid."'");
        return $list;
    }
    
    public function ifCodeExist($code) {
        $list = $this->con->get_results("select 1 from USER where code='".$code."'");
        if(!empty($list)){
            return true;
        }else{
            return false;
        }
    }

}