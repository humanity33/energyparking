<?php
include_once("Connection.php");
class Login {
	public function execute() {
		global $_POST;
		if(!isset($_POST["user"]) || !isset($_POST["md5pass"]))
			return "false";
		$user = str_replace("'","\'",htmlentities(trim($_POST["user"])));
		$pass = str_replace("'","\'",htmlentities(trim($_POST["md5pass"])));
		$conn = new Connection();
		$conn->connect();
		$select = $conn->query("select firstName, lastName from driver where user='{$user}' AND pass='{$pass}'");
		$conn->close();
		if(count($select) == 1)
			return "true".json_encode($select);
		return "false";
	}
}
exit((new Login())->execute());
?>