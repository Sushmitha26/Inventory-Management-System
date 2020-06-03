<?php
//user class for account creation and login
class User {
	
	private $con;
	function __construct()
	{
		include_once("../database/db.php");
		$db = new Database();   //object
		$this->con = $db->connect();  //returns connection object

		/*if($this->con) {
			echo "Connected";
		}*/
	}
		private function emailExists($email) {
			$pre_stmt = $this->con->prepare("SELECT id FROM user WHERE email = ? ");//don't put ur variable in sql query,this prevents sql attack
			$pre_stmt->bind_param("s",$email);  //here,we have to replace the ? with variable,s->string type
			$pre_stmt->execute() or die($this->con->error);//execute sql stmt,die tells if anything wrong in sql
			$result = $pre_stmt->get_result();
			if($result->num_rows > 0) {  //get no.of rows
				return 1;   //user already registered
			}else{
				return 0;
			}

		}

		public function createUserAccount($username,$email,$password,$usertype){ //public coz called outside class
			//to protect ur application from sql attack,u can use prepares statement
			if($this->emailExists($email)) {
				return "EMAIL_ALREADY_EXISTS";
			}
			else{
				$pass_hash = password_hash($password, PASSWORD_BCRYPT,["cost"=>8]);  //hashing the password,Use the CRYPT_BLOWFISH algorithm to create the hash(2y algorithm). The result will always be a 60 character string, or FALSE on failure.default cost-10,Increasing the cost parameter by 1, doubles the needed time to calculate the hash value. The cost parameter is the logarithm (base-2) of the iteration count 
				$date = date("Y-m-d");
				$notes = "";
				$pre_stmt = $this->con->prepare("INSERT INTO `user`(`username`, `email`, `password`, `usertype`, `register_date`, `last_login`, `notes`) VALUES (?,?,?,?,?,?,?)");
				$pre_stmt->bind_param("sssssss",$username,$email,$pass_hash,$usertype,$date,$date,$notes);  //7 variables,so 7 string types-s
				$result = $pre_stmt->execute() or die($this->con->error);
				if($result) {
					return $this->con->insert_id;
				}else{
					return "SOME_ERROR";
				}
			}
		}

		public function userLogin($email,$password) {
			$pre_stmt = $this->con->prepare("SELECT id,username,password,last_login,usertype FROM user WHERE email = ? ");
			$pre_stmt->bind_param("s",$email);
			$pre_stmt->execute() or die($this->con->error);
			$result = $pre_stmt->get_result();
			if($result->num_rows < 1) {
				return "NOT_REGISTERED";
			}else {
				$row = $result->fetch_assoc();//fetches a result row as an associative array.lyk key value
				if(password_verify($password, $row["password"])) {
					//begin the session for user
					$_SESSION["userid"] = $row["id"];
					$_SESSION["username"] = $row["username"];
					$_SESSION["last_login"] = $row["last_login"];
					$_SESSION["usertype"] = $row["usertype"];

					//update last login
					$last_login = date("Y-m-d h:m:s");  //time when user logged in is taken by date function
					$pre_stmt = $this->con->prepare("UPDATE user SET last_login = ? WHERE email = ?");
					$pre_stmt->bind_param("ss",$last_login,$email);
					$result = $pre_stmt->execute() or die($this->con->error);
					if($result) {
						return 1;
					}else {
						return 0;
					}
				}else {
					return "PASSWORD DID NOT MATCH";
				}
			}
		}
}

//$user = new User(); //on object creation,firstly constructor is called.
//echo $user->createUserAccount("Kushala","kushala@gmail.com","blahblah","Admin");

//echo $user->userLogin("sushmithacg.20@gmail.com","heelo");
//echo $_SESSION["username"];
?>