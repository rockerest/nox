<?php
	namespace model;
	class Authentication extends \model\Base{
		public static function validate($identity, $password){
			$base = new \model\Base();

			$sql = "SELECT salt FROM authentication WHERE identity=?";
			$values = array($identity);
			$res = $base->db->qwv($sql, $values);

			$saltPass = hash('whirlpool', $res[0]['salt'].$password);

			$sql = "SELECT * FROM authentication WHERE identity=? AND password=?";
			$values = array($identity, $saltPass);
			$res = $base->db->qwv($sql, $values);

			if( count($res) != 1 ){
				return false;
			}
			else{
				$user = \model\User::getByID($res[0]['userid']);

				if( !$user->Authentication->disabled ){
					return $user;
				}
				else{
					return false;
				}
			}
		}

		public static function checkIdentity($ident){
			$base = new \model\Base();

			$sql = "SELECT authenticationid FROM authentication WHERE identity=?";
			$values = array($ident);
			$res = $base->db->qwv($sql, $values);

			return count($res);
		}

		public static function addForUser($id, $ident, $pass, $roleid){
			$vals = \model\Authentication::hash($pass);

			$auth = new \model\Authentication(null, $ident, $vals[0], $vals[1], $id, $roleid, 0, 0);
			$save = $auth->save();
			if( $save ){
				return $auth;
			}
			else{
				return false;
			}
		}

		public static function getByIdentity($ident){
			$base = new \model\Base();

			$sql = "SELECT * FROM authentication WHERE identity=?";
			$values = array($ident);
			$res = $base->db->qwv($sql, $values);

			return \model\Authentication::wrap($res);
		}

		public static function getByUserID($id){
			$base = new \model\Base();

			$authSQL = "SELECT * FROM authentication WHERE userid=?";
			$values = array($id);
			$auth = $base->db->qwv($authSQL, $values);

			return \model\Authentication::wrap($auth);
		}

		public static function disableByUserID($id){
			$base = new \model\Base();

			$sql = "UPDATE authentication SET disabled=1 WHERE userid=?";
			$values = array($id);
			$del = $base->db->qwv($sql, $values);

			return $base->db->stat();
		}

		public static function enableByUserID($id){
			$base = new \model\Base();

			$sql = "UPDATE authentication SET disabled=0 WHERE userid=?";
			$values = array($id);
			$del = $base->db->qwv($sql, $values);

			return $base->db->stat();
		}

		public static function forcePasswordChangeByUserID($id){
			$base = new \model\Base();

			$sql = "UPDATE authentication SET resetPassword=1 WHERE userid=?";
			$values = array($id);
			$del = $base->db->qwv($sql, $values);

			return $base->db->stat();
		}

		public static function acceptPasswordByUserID($id){
			$base = new \model\Base();

			$sql = "UPDATE authentication SET resetPassword=0 WHERE userid=?";
			$values = array($id);
			$del = $base->db->qwv($sql, $values);

			return $base->db->stat();
		}

		public static function deleteByUserID($id){
			$base = new \model\Base();

			$delSQL = "DELETE FROM authentication WHERE userid=?";
			$values = array($id);
			$del = $base->db->qwv($delSQL, $values);

			return $base->db->stat();
		}

		public static function wrap($auths){
			$authList = array();
			foreach( $auths as $auth ){
				array_push($authList, new \model\Authentication($auth['authenticationid'], $auth['identity'], $auth['salt'], $auth['password'], $auth['userid'], $auth['roleid'], $auth['resetPassword'], $auth['disabled']));
			}

			return \model\Authentication::sendback($authList);
		}

		private $authenticationid;
		private $identity;
		private $password;
		private $salt;
		private $resetPassword;
		private $disabled;

		private $roleid;
		private $userid;

		public function __construct($id, $ident, $salt, $pass, $userid, $roleid, $reset, $disabled){
			//initialize the database connection variables
			parent::__construct();

			$this->roleid = $roleid;
			$this->userid = $userid;

			$this->authenticationid = $id;
			$this->identity = $ident;
			$this->salt = $salt;
			$this->password = $pass;
			$this->resetPassword = $reset;
			$this->disabled = $disabled;
		}

		public function __get($var){
			if( strtolower($var) == 'role' ){
				return \model\Role::getByID($this->roleid);
			}
			elseif( strtolower($var) == 'user' ){
				return \model\User::getByID($this->userid);
			}
			else{
				return $this->$var;
			}
		}

		public function __set($name, $value){
			if( $name == 'salt' ){
				return false;
			}
			elseif( $name == 'password' ){
				$vals = $this->hash($value);
				$this->salt = $vals[0];
				$this->password = $vals[1];
			}
			else{
				$this->$name = $value;
			}
		}

		public function save(){
			if( isset($this->authenticationid) ){
				if( $this->allSet() ){
					$authSQL = "UPDATE authentication SET identity=?, salt=?, password=?, roleid=?, resetPassword=?, disabled=? WHERE authenticationid=? AND userid=?";
					$values = array($this->identity, $this->salt, $this->password, $this->roleid, $this->resetPassword, $this->disabled, $this->authenticationid, $this->userid);
					$this->db->qwv($authSQL, $values);

					if( $this->db->stat() ){
						return $this;
					}
					else{
						return false;
					}
				}
				else{
					return false;
				}
			}
			else{
				if( $this->allSet() ){
					$authSQL = "INSERT INTO authentication (identity, salt, password, userid, roleid, resetPassword, disabled) VALUES (?,?,?,?,?,?,?)";
					$values = array($this->identity, $this->salt, $this->password, $this->userid, $this->roleid, $this->resetPassword, $this->disabled);
					$this->db->qwv($authSQL, $values);

					if( $this->db->stat() ){
						$this->authenticationid = $this->db->last();
						return $this;
					}
					else{
						return false;
					}
				}
				else{
					return false;
				}
			}
		}

		private function allSet(){
			return isset($this->identity, $this->salt, $this->password, $this->roleid);
		}

		private function hash($pass){
			$salt = substr(hash('whirlpool',rand(100000000000, 999999999999)), 0, 64);
			$real_pass = hash('whirlpool', $salt.$pass);

			return array($salt, $real_pass);
		}
	}
?>
