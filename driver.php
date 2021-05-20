<?php
include_once("Connection.php");
class Driver {
	private $user, $pass;
	
	public function __construct() {
		global $_POST;
		$this->user = !isset($_POST["user"]) ? null : str_replace("'","\'",(trim($_POST["user"])));
		$this->pass = !isset($_POST["md5pass"]) ? null : str_replace("'","\'",(trim($_POST["md5pass"])));
	}
	public function login() {
		if($this->user === null || $this->pass === null)
			return false;
		$conn = new Connection();
		$conn->connect();
		$select = $conn->query("select * from driver where user='{$this->user}' AND pass='{$this->pass}'");
		$conn->close();
		return count($select) == 1;
	}
	public function getAll() {
		if(!$this->login())
			return "false";
		$conn = new Connection();
		$conn->connect();
		$select = $conn->query("select * from driver");
		$conn->close();
		return json_encode($select, JSON_UNESCAPED_UNICODE);
	}
	public function update() {
		global $_POST;
		if(!$this->login())
			return "false";
		$conn = new Connection();
		$conn->connect();
		if(!isset($_POST["lastNameold"]) || !isset($_POST["firstNameold"]) || !isset($_POST["emailold"]) || !isset($_POST["lastName"]) || !isset($_POST["firstName"]) || !isset($_POST["email"]))
			return "false";
		$firstName = str_replace("'","\'",(trim($_POST["firstName"])));
		$lastName = str_replace("'","\'",(trim($_POST["lastName"])));
		$email = str_replace("'","\'",(trim($_POST["email"])));

		$firstNameold = str_replace("'","\'",(trim($_POST["firstNameold"])));
		$lastNameold = str_replace("'","\'",(trim($_POST["lastNameold"])));
		$emailold = str_replace("'","\'",(trim($_POST["emailold"])));

		$select_notfound = $conn->query("select * from driver where em='{$emailold}'");
		if(count($select_notfound) < 1) {
			$conn->close();
			return "notfound";
		}
		$unique = $select_notfound[0]["em"];
		$select = $conn->query_static("update driver set em='{$email}', firstName='{$firstName}', lastName='{$lastName}' where em='{$unique}'");
		$conn->close();
		if($select == true)
			return "true";
		return "false";
	}
	public function delete() {
		global $_POST;
		if(!$this->login())
			return "false";
		if(!isset($_POST["lastName"]) || !isset($_POST["firstName"]) || !isset($_POST["email"]))
			return "false";
		$conn = new Connection();
		$conn->connect();

		$firstName = str_replace("'","\'",(trim($_POST["firstName"])));
		$lastName = str_replace("'","\'",(trim($_POST["lastName"])));
		$email = str_replace("'","\'",(trim($_POST["email"])));
		$select_notfound = $conn->query("select * from driver where em='{$email}' AND firstName='{$firstName}' AND lastName='{$lastName}'");
		if(count($select_notfound) < 1) {
			$conn->close();
			return "notfound";
		}

		$select = $conn->query_static("delete from driver where em='{$email}' AND firstName='{$firstName}' AND lastName='{$lastName}'");
		$conn->close();
		if($select == true)
			return "true";
		return "false";
	}
	public function store() {
		global $_POST;
		if(!$this->login())
			return "false";
		if(!isset($_POST["lastName"]) || !isset($_POST["firstName"]) || !isset($_POST["email"]))
			return "false";
		$conn = new Connection();
		$conn->connect();

		$firstName = str_replace("'","\'",(trim($_POST["firstName"])));
		$lastName = str_replace("'","\'",(trim($_POST["lastName"])));
		$email = str_replace("'","\'",(trim($_POST["email"])));
		$select = $conn->query_static("INSERT INTO /*`energyparking`.*/`taxpayer` (`id`, `lastName`, `firstName`, `email`) VALUES (NULL, '{$lastName}', '{$firstName}', '{$email}');");
		$conn->close();
		if($select == true)
			return "true";
		return "false";
	}	
}
?>