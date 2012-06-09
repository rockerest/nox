<?php
	namespace backbone;
	class Session{
		protected $expiration;
		protected $root;
		protected $domain;
		protected $secure;
		protected $httponly;

		public function __construct( $expire = 0, $root = '/', $dom = null, $secure = false, $httponly = true ){
			$this->expiration	= $expire;
			$this->root			= $root;
			$this->domain		= $dom;
			$this->secure		= $secure;
			$this->httponly		= $httponly;
		}

		public function setSession(){
			if( $this->domain == null ){
				$this->domain = '.' . $_SERVER['HTTP_HOST'];
			}

			session_set_cookie_params( $this->expiration, $this->root, $this->domain, $this->secure, $this->httponly );

			if( !isset( $_COOKIE['identifier'] ) ){
				$myTempVar = uniqid(TRUE);
				$sesswhirl = substr( hash('whirlpool', $myTempVar), 0, 68 );

				setcookie('identifier', $sesswhirl);
				session_name($sesswhirl);
				session_start();
			}
			else{
				if( !isset( $_SESSION ) ){
					session_name($_COOKIE['identifier']);
					session_start();
				}
			}
		}

		public function setSessionVar($name, $value){
			$_SESSION[$name] = $value;
		}
	}
