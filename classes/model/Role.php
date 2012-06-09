<?php
	namespace model;
	class Role extends \model\Base{
		public static function getAll(){
			$base = new \model\Base();

			$sql = "SELECT * FROM roles";
			$res = $base->db->q($sql);

			return \model\Role::wrap($res);
		}

		public static function getByID($id){
			$base = new \model\Base();

			$roleSQL = "SELECT * FROM roles WHERE roleid=?";
			$values = array($id);
			$role = $base->db->qwv($roleSQL, $values);

			return \model\Role::wrap($role);
		}

		public static function getByName($name){
			$base = new \model\Base();

			$roleSQL = "SELECT * FROM roles WHERE name LIKE '%?%'";
			$values = array($name);
			$role = $base->db->qwv($roleSQL, $values);

			return \model\Role::wrap($role);
		}

		public static function add($name, $description){
			$role = new \model\Role(null, $description, $name);
			$res = $role->save();

			if( $res ){
				return $res;
			}
			else{
				return false;
			}
		}

		public static function deleteByID($id){
			$base = new \model\Base();

			//don't allow roles to be deleted if users still have that role
			$sql = "SELECT * FROM authentication WHERE roleid=?";
			$values = array($id);
			$res = $base->db->qwv($sql, $values);

			if( count($res) > 0 ){
				return false;
			}
			else{
				$sql = "DELETE FROM roles WHERE roleid=?";
				$values = array($id);
				$base->db->qwv($sql, $values);

				return $base->db->stat();
			}
		}

		public static function wrap($roles){
			$roleList = array();
			foreach( $roles as $role ){
				array_push($roleList, new \model\Role($role['roleid'], $role['description'], $role['name']));
			}

			return \model\Role::sendback($roleList);
		}

		private $roleid;
		private $description;
		private $name;

		public function __construct($roleid, $description, $name){
			//initialize the database connection variables
			parent::__construct();

			$this->roleid = $roleid;
			$this->description = $description;
			$this->name = $name;
		}

		public function __get($var){
			return $this->$var;
		}

		public function __set($n, $v){
			$this->$n = $v;
		}

		public function save(){
			if( !isset($this->roleid) ){
				$sql = "INSERT INTO roles (name, description) VALUES(?,?)";
				$values = array($this->name, $this->description);
				$this->db->qwv($sql, $values);

				if( $this->db->stat() ){
					$this->roleid = $this->db->last();
					return $this;
				}
				else{
					return false;
				}
			}
			else{
				$sql = "UPDATE roles SET name=?, description=? WHERE roleid=?";
				$values = array ($this->name, $this->description, $this->roleid);
				$this->db->qwv($sql, $values);

				return $this->db->stat();
			}
		}
	}
?>
