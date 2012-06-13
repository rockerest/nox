<?php
	namespace model\objects;
	class Role extends \model\objects\ObjectBase{
		protected $roleid;
		protected $description;
		protected $name;

		public function __construct($roleid, $description, $name, $roleDA ){
			parent::__construct();

			$this->roleid = $roleid;
			$this->description = $description;
			$this->name = $name;

			$this->accessors['Main'] = $roleDA;
		}

		// Setters
		public function setRoleId( $id ){
			if( !isset( $this->roleid ) ){
				$this->roleid = $id;
			}

			return $this;
		}

		public function setDescription( $description ){
			$this->description = $description;
			return $this;
		}

		public function setName( $name ){
			$this->name = $name;
			return $this;
		}

		// Getters
		public function getRoleId(){
			return $this->roleid;
		}

		public function getDescription(){
			return $this->description;
		}

		public function getName(){
			return $this->name;
		}
	}
?>
