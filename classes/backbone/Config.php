<?php
	namespace backbone;
	class Config{
		private $filename;

		protected $config;
		protected $root;
		protected $session;

		public function __construct( $conf = null, $root = null ){
			$this->root = ($root == null) ? APPLICATION_ROOT_URL : $root;
			$this->conf = ($conf == null) ? APPLICATION_CONFIGURATION_FILE : $conf;
			// HACK: This constructor could fail.
			$this->loadConfig();
		}

		public function loadConfig(){
			$options = file( APPLICATION_ROOT_PATH . "components/system/" . $this->conf, FILE_USE_INCLUDE_PATH);
			foreach( $options as $conf_entry ){
				eval($conf_entry);
			}

			$this->config = $config;
		}
	}
