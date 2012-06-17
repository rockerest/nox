<?php
	namespace model\objects;
	use model\access;
	class Quick_Login extends ObjectBase{
		protected $quick_loginid;
		protected $hash;
		protected $userid;
		protected $expires;
		protected $used;

		public function __construct($quick_loginid, $hash, $userid, $expires, $used, $qlDA = null, $userDA = null){
			parent::__construct();

			$this->quick_loginid = $quick_loginid;
			$this->hash = $hash;
			$this->userid = $userid;
			$this->expires = $expires;
			$this->used = $used;

			$this->accessors['Main'] = isset( $qlDA ) ? $qlDA : new access\Quick_LoginAccess;
			$this->accessors['Users'] = isset( $userDA ) ? $userDA : new access\UserAccess;
		}

		// Setters
		public function setQuick_LoginId( $id ){
			if( !isset( $this->quick_loginid ) ){
				$this->quick_loginid = $id;
			}

			return $this;
		}

		public function setHash( $hash ){
			$this->hash = $hash;
			return $this;
		}

		public function setUserId( $userid ){
			$this->userid = $userid;
			return $this;
		}

		public function setExpires( $expires ){
			$this->expires = $expires;
			return $this;
		}

		public function setUsed( $used ){
			$this->used = $used;
			return $this;
		}


		// Getters
		public function getQuick_LoginId(){
			return $this->quick_loginid;
		}

		public function getHash(){
			return $this->hash;
		}

		public function getUserId(){
			return $this->userid;
		}

		public function getExpires(){
			return $this->expires;
		}

		public function getUsed(){
			return $this->used;
		}

		// Extrapolated Getters
		public function getUser(){
			return $this->accessors['Users']->getById( $this->userid );
		}
	}
?>
