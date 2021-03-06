<?php 
//require("dbcon.php");

class Users{

	public function __construct() {           
		$this->mysqcon= new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);;
		}

	public function getUser($id){ 
        //$stmt = $this->mysqcon->open();
        //$stmt=$this->mysqcon->open();     
        $stmt=$this->mysqcon->prepare("SELECT * FROM tb_users WHERE id=? ; ");
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $result = $stmt->get_result();        
        $peoples = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $peoples;              
    }

    public function login($email){ 
        //$stmt = $this->mysqcon->open();
        //$stmt=$this->mysqcon->open();     
        $stmt=$this->mysqcon->prepare("SELECT u.id,u.name, u.lastname, u.nickname, u.email,
                                u.birthdate, u.cellphone, u.documentid, t.token,
                                p.emailReceiver,p.photoPublication,p.enableReportUbication,p.enableLocationUbication
                                from tb_users u
                                LEFT JOIN tb_tokenUsers t
                                on u.id=t.id
                                LEFT JOIN tb_userConfigurations p
                                on u.id=p.idUser
                                where email like ?  and t.isValid=1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();        
        $peoples = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $peoples;              
    }
    public function setTokenByUser($email,$token,$loggedWeb,$loggedMobile){ 
        //$stmt = $this->mysqcon->open();
        //$stmt=$this->mysqcon->open();
        $stmt = $this->mysqcon->prepare('SET @email := ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();

        // bind the second parameter to the session variable @userCount
        $stmt = $this->mysqcon->prepare('SET @token := ?');
        $stmt->bind_param('s', $token);
        $stmt->execute();

        $stmt = $this->mysqcon->prepare('SET @loggedMobile := ?');
        $stmt->bind_param('i', $loggedMobile);
        $stmt->execute();

        $stmt = $this->mysqcon->prepare('SET @loggedWeb := ?');
        $stmt->bind_param('i', $loggedWeb);
        $stmt->execute();

        $stmt=$this->mysqcon->query("call sp_setTokenByUser(@email,@token,@loggedWeb,@loggedMobile)");
              
    }
    public function logout($token,$loggedWeb,$loggedMobile){ 
        try{
           //$stmt = $this->mysqcon->open();
            //$stmt=$this->mysqcon->open();

            // bind the second parameter to the session variable @userCount
            $stmt = $this->mysqcon->prepare('SET @token := ?');
            $stmt->bind_param('s', $token);
            $stmt->execute();

            $stmt = $this->mysqcon->prepare('SET @loggedMobile := ?');
            $stmt->bind_param('i', $loggedMobile);
            $stmt->execute();

            $stmt = $this->mysqcon->prepare('SET @loggedWeb := ?');
            $stmt->bind_param('i', $loggedWeb);
            $stmt->execute();

            $stmt=$this->mysqcon->query("call sp_logout(@token,@loggedWeb,@loggedMobile)"); 
        }
        catch(Exception $e){
            return $e->getmessage();
        }

              
    }
    public function setTokenRecoverPass($token,$id){ 
        //$stmt = $this->mysqcon->open();
        //$stmt=$this->mysqcon->open();
        $stmt = $this->mysqcon->prepare('UPDATE tb_tokenUsers set token=?,isValid=0 WHERE id=?');
        $stmt->bind_param('si', $token,$id);
        $stmt->execute();
        $user=$stmt->affected_rows;
        return $user;
             
    }
    public function setPassword($password,$id){ 
        //$stmt = $this->mysqcon->open();
        //$stmt=$this->mysqcon->open();
        $stmt = $this->mysqcon->prepare('UPDATE tb_users set password=? WHERE id=?');
        $stmt->bind_param('si', $password,$id);
        $stmt->execute();
        $user=$stmt->affected_rows;
        return $user;
             
    }
    public function getTokenRecovery($email){ 
        //$stmt = $this->mysqcon->open();
        //$stmt=$this->mysqcon->open();     
        $stmt=$this->mysqcon->prepare("SELECT t.token from tb_tokenUsers t
                                INNER JOIN tb_users u ON t.id=u.id
                                WHERE u.email like ? and t.isValid=0");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();        
        $peoples = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $peoples;              
    }
    // Configura las preferencias del usuario
    public function setPreferences($token,$emailReceiver,$photoPublication,$enableReportUbication,$enableLocationUbication){ 
        // bind the second parameter to the session variable @userCount
        $stmt = $this->mysqcon->prepare('SET @token := ?');
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt = $this->mysqcon->prepare('SET @emailReceiver := ?');
        $stmt->bind_param('i', $emailReceiver);
        $stmt->execute();
        $stmt = $this->mysqcon->prepare('SET @photoPublication := ?');
        $stmt->bind_param('i', $photoPublication);
        $stmt->execute();
        $stmt = $this->mysqcon->prepare('SET @enableReportUbication := ?');
        $stmt->bind_param('i', $enableReportUbication);
        $stmt->execute();
        $stmt = $this->mysqcon->prepare('SET @enableLocationUbication := ?');
        $stmt->bind_param('i', $enableLocationUbication);
        $stmt->execute();


        $stmt=$this->mysqcon->query("call sp_setPreferences(@token,@emailReceiver,@photoPublication,@enableReportUbication,@enableLocationUbication)");
              
    }
    public function getPassword($email){ 
        //$stmt = $this->mysqcon->open();
        //$stmt=$this->mysqcon->open();     
        $stmt=$this->mysqcon->prepare("SELECT id,password from tb_users where email like ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();        
        $peoples = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $peoples;              
    }

    /**
    * obtiene todos los registros de la tabla "people"
    * @return Array array con los registros obtenidos de la base de datos
    */
    public function getUsers(){ 

        $result=$this->mysqcon->query('SELECT * FROM tb_users');          
        $peoples = $result->fetch_all(MYSQLI_ASSOC);          
        $result->close();
        return $peoples; 
    }
    //obtiene el id del usuario por medio del token
    public function getUserId($token){ 

        $stmt=$this->mysqcon->prepare('SELECT id FROM tb_tokenUsers where token like ? and isValid =1');
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $result = $stmt->get_result();        
        $peoples = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $peoples; 
    }
    //obtiene el id del usuario por medio del token
    /*public function getUserName($token){ 

        $stmt=$this->mysqcon->prepare('SELECT u.id,u.nickname,u.email FROM tb_users u
                            INNER JOIN tb_tokenUsers t
                            ON u.id=t.id where t.token like ? and t.isValid =1');
        $stmt->bind_param('s', $token);
        $stmt->execute();
        print_r($stmt);
        $result = $stmt->get_result();        
        $peoples = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $peoples; 
    }*/
    public function getUserName($token){ 
//$stmt = $this->mysqcon->open();
        $stmt=$this->mysqcon;
        $stmt=$this->mysqcon->prepare('SET @token := ?');
        $stmt->bind_param('s', $token);
        $stmt->execute();
        //print_r($userName);
        $stmt=$this->mysqcon->query("call sp_getUserName(@token)");
        //print_r($stmt);
        $result = $stmt->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $result; 
    }

    //Registra al usuario
    public function registerUser($name,$lastname,$nickname,$email,$birthdate,$cellphone,$documentid,$password){
    	
        $stmt=$this->mysqcon->prepare("INSERT INTO tb_users(name,lastName,nickname,email,
                        birthdate,cellphone,documentid,password)
                        VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssssss', $name,$lastname,$nickname,$email,
                    $birthdate,$cellphone,$documentid,$password);
        $r = $stmt->execute(); 
        $stmt->close();
        return $r;        
    }

    private function close(){
        try{
            $this->mysqcon->close();
            return true;
        }catch(mysqli_sql_exception $e){
            return $e;
        }
    }
    public function updatePassword($id,$newPassword) {
      
            $stmt = $this->mysqcon;
            $stmt =$this->mysqcon->prepare("UPDATE tb_users SET password=? WHERE id = ? ");
            $stmt->bind_param('si', $newPassword,$id);
            $r = $stmt->execute(); 
            $stmt->close();
            return $r;    
    }


    /**
     * verifica si un ID existe
     * @param int $id Identificador unico de registro
     * @return Bool TRUE|FALSE
     */
    public function checkEmail($email){
    	$stmt = $this->mysqcon;
        $stmt ->prepare("SELECT * FROM tb_users WHERE email like ?");
        $stmt->bind_param("s", $email);
        if($stmt->execute()){
            $stmt->store_result();    
            if ($stmt->num_rows == 1){                
                return true;
            }
        }        
        return false;
    }

    //comprueba si existe el usuario en base de datos
    function userExist($email){
        try{
            $stmt=$this->mysqcon->prepare("SELECT id from tb_users where email like ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();        
            $user=$result->fetch_all(MYSQLI_ASSOC); 
            $stmt->close();
            return $user;
        }
        catch(Execption $e){
            $this->response["error"]=true;
            $this->response["message"] = $e->getmessage();
            return $this->response;
        }
    }
        //comprueba si existe el usuario en base de datos
    function userExistToken($token){
        try{
            $stmt=$this->mysqcon->prepare("SELECT u.id from tb_users u 
                                INNER JOIN tb_tokenUsers tu on tu.id=u.id where tu.token like ?");
            $stmt->bind_param('s', $token);
            $stmt->execute();
            $result = $stmt->get_result();        
            $user=$result->fetch_all(MYSQLI_ASSOC); 
            $stmt->close();
            return $user;
        }
        catch(Execption $e){
            $this->response["error"]=true;
            $this->response["message"] = $e->getmessage();
            return $this->response;
        }
    }

}
?>