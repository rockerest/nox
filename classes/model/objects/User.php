<?php
	namespace model\objects;
	use model\access;
	class User extends ObjectBase{
		protected $userid;
		protected $fname;
		protected $lname;
		protected $gender;

		public function __construct($userid, $fname, $lname, $gender, $userDA = null, $authDA = null, $contDA = null ){
			parent::__construct();

			$this->userid = $userid;
			$this->fname = $fname;
			$this->lname = $lname;
			$this->gender = $gender;

			$this->accessors['Main'] = isset($userDA) ? $userDA : new access\UserAccess;
			$this->accessors['Authentication'] = isset($authDA) ? $authDA : new access\AuthenticationAccess;
			$this->accessors['Contact'] = isset($contDA) ? $contDA : new access\ContactAccess;
		}

		// Setters
		public function setUserId( $id ){
			if( !isset( $this->userid ) ){
				$this->userid = $id;
			}

			return $this;
		}

		public function setFname( $fname ){
			$this->fname = $fname;
			return $this;
		}

		public function setLname( $lname ){
			$this->lname = $lname;
			return $this;
		}

		public function setGender( $gender ){
			$this->gender = $gender;
			return $this;
		}

		// Getters
		public function getUserId(){
			return $this->userid;
		}

		public function getFname(){
			return $this->fname;
		}

		public function getLname(){
			return $this->lname;
		}

		public function getGender(){
			return $this->gender;
		}

		// data information
		public function getFullName(){
			return $this->fname . " " . $this->lname;
		}

		// Extrapolated Getters
		public function getContact(){
			return $this->accessors['Contact']->getByUserId( $this->userid );
		}

		public function getAuthentication(){
			return $this->accessors['Authentication']->getByUserId( $this->userid );
		}
	}
?>
