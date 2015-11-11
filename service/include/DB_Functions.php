<?php
 
class DB_Functions {
 
    private $db;
 
    //put your code here
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }
 
    // destructor
    function __destruct() {
         
    }
 
    /**
     * Get user by email and password
     */
    public function getUserByLoginAndPassword($login, $password) {
        $result = mysql_query("SELECT * FROM user WHERE login = '$login'") or die(mysql_error());
        // check for result 
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            $encrypted_password = $result['password'];
            $hash = $this->hashmd5($password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }
    }
 
    public function hashmd5($password) {
 
        $hash = md5($password);
        return $hash;
    }

    // Récupérer la liste des élève nom/prénom

    public function getUser(){

        $result = mysql_query("SELECT id, nom, prenom, telephone, mail, promotion FROM utilisateurs") or die(mysql_error());
        $int_num_field = mysql_num_fields($result);
        $json = array();

       while($row = mysql_fetch_array($result)){
            $arr_col = array();
            for($i=0;$i<$int_num_field;$i++)
            {
                    $arr_col[mysql_field_name($result,$i)] = $row[$i];    
            }
            array_push($json, $arr_col);
       }
       
       return $json;
    }

    public function getProductList(){
        $result = mysql_query("SELECT * FROM product") or die(mysql_error());
        $json = array();
                $rows = array();
                while($r = mysql_fetch_array($result)) {
                    $rows[] = $r;
                }
        return $rows;
    }

    public function deletProduct($ProductId){
        $result = mysql_query("DELETE FROM product WHERE ProductId = '$ProductId'");
    }

    public function addToCart($productId)
    {
        
    }
    // public function getCartUserQuantity(){
    //     $result = mysql_query("SELECT quantity")
    // }
 
}
 
?>