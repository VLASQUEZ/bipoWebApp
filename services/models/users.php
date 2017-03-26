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
        $stmt=$this->mysqcon->prepare("SELECT u.name, u.lastname, u.nickname, u.email,
                                u.birthdate, u.cellphone, u.documentid, t.token from tb_users u
                                LEFT JOIN tb_tokenUsers t
                                on u.id=t.id 
                                where email like ? ");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();        
        $peoples = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $peoples;              
    }
        public function setTokenByUser($email){ 
        //$stmt = $this->mysqcon->open();
        //$stmt=$this->mysqcon->open();     
        $stmt=$this->mysqcon->prepare("SELECT u.name, u.lastname, u.nickname, u.email,
                                u.birthdate, u.cellphone, u.documentid, t.token from tb_users u
                                LEFT JOIN tb_tokenUsers t
                                on u.id=t.id 
                                where email like ? ");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();        
        $peoples = $result->fetch_all(MYSQLI_ASSOC); 
        $stmt->close();
        return $peoples;              
    }
    public function getPassword($email){ 
        //$stmt = $this->mysqcon->open();
        //$stmt=$this->mysqcon->open();     
        $stmt=$this->mysqcon->prepare("SELECT password from tb_users where email like ?");
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

/**
 * añade un nuevo registro en la tabla persona
 * @param String $name nombre completo de persona
 * @return bool TRUE|FALSE 
 */
    public function registerUser($name,$lastname,$nickname,$email,
                    $birthdate,$cellphone,$documentid,$password){
    	
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

    /**
     * Actualiza registro dado su ID
     * @param int $id Description
     */
    public function update($id, $newName) {
        if($this->checkID($id)){
        	$stmt = $this->mysqcon;
            $stmt->open();    
            $stmt ->prepare("UPDATE tb_users SET name=? WHERE id = ? ; ");
            $stmt->bind_param('ss', $newName,$id);
            $r = $stmt->execute(); 
            $stmt->close();
            return $r;    
        }
        return false;
    }

    /**
     * verifica si un ID existe
     * @param int $id Identificador unico de registro
     * @return Bool TRUE|FALSE
     */
    public function checkID($id){
    	$stmt = $this->mysqcon;
        $stmt->open();    
        $stmt ->prepare("SELECT * FROM tb_users WHERE ID=?");
        $stmt->bind_param("s", $id);
        if($stmt->execute()){
            $stmt->store_result();    
            if ($stmt->num_rows == 1){                
                return true;
            }
        }        
        return false;
    }

}
?>