<?php
	namespace model;
	class Quick_Login extends \model\Base{
		public static function getByHash($hash){
			$base = new \model\Base();

			$sql = "SELECT * FROM quick_login WHERE hash=? AND used=0 AND expires>?";
			$values = array($hash, time());
			$ql = $base->db->qwv($sql, $values);

			return \model\Quick_Login::wrap($ql);
		}

		public static function add($hash, $userid, $expires, $used){
			$ql = new \model\Quick_Login(null, $hash, $userid, $expires, $used);
			$res = $ql->save();

			if( $res ){
				return $res;
			}
			else{
				return false;
			}
		}

		public static function wrap($qls){
			$qlList = array();
			foreach( $qls as $ql ){
				array_push($qlList, new \model\Quick_Login($ql['quick_loginid'], $ql['hash'], $ql['userid'], $ql['expires'], $ql['used']));
			}

			return \model\Quick_Login::sendback($qlList);
		}

		private $quick_loginid;
		private $hash;
		private $userid;
		private $expires;
		private $used;

		public function __construct($quick_loginid, $hash, $userid, $expires, $used){
			//initialize the database connection variables
			parent::__construct();

			$this->quick_loginid = $quick_loginid;
			$this->hash = $hash;
			$this->userid = $userid;
			$this->expires = $expires;
			$this->used = $used;
		}

		public function __get($var){
			if( strtolower($var) == 'user' ){
				return \model\User::getByID($this->userid);
			}
			return $this->$var;
		}

		public function __set($n, $v){
			$this->$n = $v;
		}

		public function save(){
			if( !isset($this->quick_loginid) ){
				$sql = "INSERT INTO quick_login (hash, userid, expires, used) VALUES(?,?,?,?)";
				$values = array($this->hash, $this->userid, $this->expires, $this->used);
				$this->db->qwv($sql, $values);

				if( $this->db->stat() ){
					$this->quick_loginid = $this->db->last();
					return $this;
				}
				else{
					return false;
				}
			}
			else{
				$sql = "UPDATE quick_login SET hash=?, userid=?, expires=?, used=? WHERE quick_loginid=?";
				$values = array ($this->hash, $this->userid, $this->expires, $this->used, $this->quick_loginid);
				$this->db->qwv($sql, $values);

				return $this->db->stat();
			}
		}
	}
?>
