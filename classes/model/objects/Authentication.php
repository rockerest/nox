<?php
	namespace model\objects;
	use model\access;
	class Authentication extends ObjectBase{
		protected $authenticationid;
		protected $userid;
		protected $roleid;
		protected $identity;
		protected $password;
		protected $salt;
		protected $resetPassword;
		protected $disabled;

		public function __construct($authenticationid, $userid, $roleid, $identity, $password, $salt, $resetPassword, $disabled, $authenticationDA = null, $roleDA = null, $userDA = null ){
			parent::__construct();

			$this->authenticationid	= $authenticationid;
			$this->userid			= $userid;
			$this->roleid			= $roleid;
			$this->identity			= $identity;
			$this->password			= $password;
			$this->salt				= $salt;
			$this->resetPassword	= $resetPassword;
			$this->disabled			= $disabled;

			$this->accessors['Main'] = isset($authenticationDA) ? $authenticationDA : new access\AuthenticationAccess;
			$this->accessors['Role'] = isset($roleDA) ? $roleDA : new access\RoleAccess;
			$this->accessors['User'] = isset($userDA) ? $userDA : new access\UserAccess;
		}

		// Setters
		public function setAuthenticationId( $id ){
			if( !isset( $this->authenticationid ) ){
				$this->authenticationid = $id;
			}

			return $this;
		}

		public function setUserId( $userid ){
			$this->userid = $userid;
			return $this;
		}

		public function setRoleId( $roleid ){
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

		public function getUserId(){
			return $this->userid;
		}

		public function getRoleId(){
			return $this->roleid;
		}

		public function getIdentity(){
			return $this->identity;
		}

		public function getPassword(){
			return $this->password;
		}

		public function getSalt(){
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
