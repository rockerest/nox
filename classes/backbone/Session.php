<?php
	namespace backbone;
	class Session{
		protected $expiration;
		protected $root;
		protected $domain;
		protected $secure;
		protected $httponly;

		private $sessionId;

		public function __construct( $expire = 0, $root = '/', $dom = null, $secure = false, $httponly = true ){
			$this->expiration	= $expire;
			$this->root			= $root;
			$this->domain		= ($dom == null) ? '.' . $_SERVER['HTTP_HOST'] : $dom;
			$this->secure		= $secure;
			$this->httponly		= $httponly;
		}

		public function setSession(){
			$name = $this->verifyName();
			$this->sessionId = $this->verifySession( $name );
		}

		public function burnCookie( $name ){
			setcookie( $name, '', time() - 101010 );
		}

		public function bakeCookie( $name, $value ){
			setcookie( $name, $value, $this->expiration, $this->root, $this->domain, $this->secure, $this->httponly );
		}

		private function verifyName(){
			if( !isset( $_COOKIE['identifier'] ) ){
				$myTempVar = uniqid(uniqid(), true);
				$sesswhirl = substr( hash('whirlpool', $myTempVar), 0, 68 );

				$this->bakeCookie('identifier', $sesswhirl );

				return $sesswhirl;
			}
			else{
				return $_COOKIE['identifier'];
			}
		}

		private function removeName(){
			if( !isset( $_COOKIE['identifier'] ) ){
				return false;
			}
			else{
				$this->burnCookie( $_COOKIE['identifier'] );
				$this->burnCookie( 'identifier' );
				return true;
			}
		}

		private function verifySession( $name ){
			if( session_name() == $name ){
				if( !isset( $_SESSION ) ){
					session_start();
				}
				return session_id();
			}
			else{
				if( session_id() != "" ){
					session_destroy();
					session_name( $name );
					session_start();
				}
				else{
					session_name( $name );
					session_start();
				}

				return session_id();
			}
		}

	}
