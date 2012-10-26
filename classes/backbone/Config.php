<?php
	namespace backbone;
	class Config{
		protected $config;

		protected $root;
		protected $conf;

		public function __construct( $conf = null, $root = null ){
			$this->root = ($root == null) ? APPLICATION_ROOT_URL : $root;
			$this->conf = ($conf == null) ? APPLICATION_CONFIGURATION_FILE : $conf;
			// HACK: This constructor could fail.
			$this->loadConfig();
		}

		public function getConfig(){
			return $this->config;
		}

		public function loadConfig(){
			$file = file_get_contents( APPLICATION_ROOT_PATH . "components/system/" . $this->conf, FILE_USE_INCLUDE_PATH);
			$this->config = json_decode( $file );
		}
	}
