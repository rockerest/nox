<?php
	namespace model\objects;
	class Contact extends \model\objects\ObjectBase{
		protected $contactid;
		protected $userid;
		protected $phone;
		protected $email;

		public function __construct($contactid, $userid, $phone, $email, $contactDA, $userDA ){
			parent::__construct();

			$this->contactid = $contactid;
			$this->userid = $userid;
			$this->phone = $phone;
			$this->email = $email;

			$this->accessors['Main'] = $contactDA;
			$this->accessors['User'] = $userDA;
		}

		// Setters
		public function setContactId( $id ){
			if( !isset( $this->contactid ) ){
				$this->contactid = $id;
			}

			return $this;
		}

		public function setUserId( $userid ){
			$this->userid = $userid;
			return $this;
		}

		public function setPhone( $phone ){
			$this->phone = $phone;
			return $this;
		}

		public function setEmail( $email ){
			$this->email = $email;
			return $this;
		}

		// Getters
		public function getContactId(){
			return $this->contactid;
		}

		public function getUserId(){
			return $this->userid;
		}

		public function getPhone(){
			return $this->phone;
		}

		public function getEmail(){
			return $this->email;
		}

		// Data Information
		public function getFriendlyPhone(){
			$phone = $this->phone;
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

		// Extrapolated Getters
		public function getUser(){
			return $this->accessors['User']->getById( $this->userid );
		}
	}
?>
