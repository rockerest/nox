<?php
	namespace model\objects;
	class Authentication extends \model\objects\ObjectBase{
		protected $authenticationid;
		protected $userid;
		protected $roleid;
		protected $identity;
		protected $password;
		protected $salt;
		protected $resetPassword;
		protected $disabled;

		public function __construct($authenticationid, $userid, $roleid, $identity, $password, $salt, $resetPassword, $disabled, $authenticationDA, $roleDA, $userDA ){
			parent::__construct();

			$this->authenticationid	= $authenticationid;
			$this->userid			= $userid;
			$this->roleid			= $roleid;
			$this->identity			= $identity;
			$this->password			= $password;
			$this->salt				= $salt;
			$this->resetPassword	= $resetPassword;
			$this->disabled			= $disabled;

			$this->accessors['Main'] = $authenticationDA;
			$this->accessors['Role'] = $roleDA;
			$this->accessors['User'] = $userDA;
		}

		// Setters
		public function setAuthenticationId( $id ){
			if( !isset( $this->authenticationid ) ){
				$this->authenticationid = $id;
			}

			return $this;
		}

		public function setUserid( $userid ){
			$this->userid = $userid;
			return $this;
		}

		public function setRoleid( $roleid ){
			$this->roleid = $roleid;
			return $this;
		}

		public function setIdentity( $identity ){
			$this->identity = $identity;
			return $this;
		}

		public function setPassword( $password ){
			$vars = $this->accessors['Main']->hash( $password );
			$this->salt = $vars[0];
			$this->password = $vars[1];
			return $this;
		}

		public function setSalt( $salt ){
			$this->salt = $salt;
			return $this;
		}

		public function setResetPassword( $resetpassword ){
			$this->resetPassword = $resetpassword;
			return $this;
		}

		public function setDisabled( $disabled ){
			$this->disabled = $disabled;
			return $this;
		}

		// Getters
		public function getAuthenticationId(){
			return $this->authenticationid;
		}

		public function getUserid(){
			return $this->userid;
		}

		public function getRoleid(){
			return $this->roleid;
		}

		public function getIdentity(){
			return $this->identity;
		}

		public function getPassword(){
			return $this->password;
		}

		public function getSalt( $salt ){
			return $this->salt;
		}

		public function getResetPassword(){
			return $this->resetPassword;
		}

		public function getDisabled(){
			return $this->disabled;
		}

		// Data Information
		public function allSet(){
			return isset($this->identity, $this->salt, $this->password, $this->roleid);
		}

		// Extrapolated Getters
		public function getRole(){
			return $this->accessors['Role']->getById( $this->roleid );
		}

		public function getUser(){
			return $this->accessors['User']->getById( $this->userid );
		}
	}
?>
