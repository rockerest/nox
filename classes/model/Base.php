<?php
	namespace model;
	class Base extends \backbone\Config{
		public static function connect(){
			return new \model\Base();
		}

		protected $db;

		public function __construct(){
			parent::__construct();

			$this->db = new \backbone\Database(	$this->config['db']['user'],
												$this->config['db']['pass'],
												$this->config['db']['dbname'],
												$this->config['db']['host'],
												'mysql'
											);
		}

		public function sendback($objects){
			if( count( $objects ) > 1 ){
				return $objects;
			}
			elseif( count( $objects ) == 1 ){
				return $objects[0];
			}
			else{
				return false;
			}
		}

		public function toArray($objects){
			if( is_array($objects) ){
				return $objects;
			}
			elseif( is_object($objects) ){
				return array($objects);
			}
			else{
				return array();
			}
		}
	}
?>
