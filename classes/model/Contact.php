<?php
	namespace model;
	class Contact extends \model\Base{
		public static function getByID($id){
			$base = new \model\Base();

			$sql = "SELECT * FROM contact WHERE contactid=?";
			$values = array($id);
			$cont = $base->db->qwv($sql, $values);

			return \model\Contact::wrap($cont);
		}

		public static function getByUserID($id){
			$base = new \model\Base();

			$sql = "SELECT * FROM contact WHERE userid=?";
			$values = array($id);
			$cont = $base->db->qwv($sql, $values);

			return \model\Contact::wrap($cont);
		}

		public static function addForUserID($id, $email, $phone = null){
			$cont = new \model\Contact(null, $id, $phone, $email);
			return $cont->save();
		}

		public static function deleteByUserID($userid){
			$base = new \model\Base();

			$sql = "DELETE FROM contact WHERE userid=?";
			$values = array($userid);
			$base->db->qwv($sql, $values);

			return $base->db->stat();
		}

		public static function wrap($contact){
			$contactList = array();
			foreach( $contact as $cont ){
				array_push($contactList, new \model\Contact($cont['contactid'], $cont['userid'], $cont['phone'], $cont['email']));
			}

			return \model\Contact::sendback($contactList);
		}


		private $contactid;
		private $userid;
		private $phone;
		private $email;

		public function __construct($contactid, $userid, $phone, $email){
			//initialize the database connection variables
			parent::__construct();

			$this->contactid = $contactid;
			$this->userid = $userid;
			$this->phone = $phone;
			$this->email = $email;
		}

		public function __get($var){
			if( strtolower($var) == 'friendlyphone' ){
				return $this->prettyPhone($this->phone);
			}
			else{
				return $this->$var;
			}
		}

		public function __set($var, $val){
			$this->$var = $val;
		}

		public function save(){
			if( !isset($this->contactid) ){
				$sql = "INSERT INTO contact (userid, phone, email) VALUES(?,?,?)";
				$values = array($this->userid, $this->phone, $this->email);
				$this->db->qwv($sql, $values);

				if( $this->db->stat() ){
					$this->contactid = $this->db->last();
					return $this;
				}
				else{
					return false;
				}
			}
			else{
				$sql = "UPDATE contact SET userid=?, phone=?, email=? WHERE contactid=?";
				$values = array ($this->userid, $this->phone, $this->email, $this->contactid);
				$this->db->qwv($sql, $values);

				return $this->db->stat();
			}
		}

		private function prettyPhone($phone){
			$phlen = strlen($phone);
			if( $phlen == 10 || $phlen == 11 ){
				//assume US
				if( $phlen == 10 ){
					$pretty = substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);
				}
				elseif( $phlen == 11 ){
					$pretty = '+' . substr($phone, 0, 1) . '-' . substr($phone, 1, 3) . '-' . substr($phone, 4, 3) . '-' . substr($phone, 7, 4);
				}

				return $pretty;
			}
			else{
				//assume International
				return $phone;
			}
		}
	}
?>
