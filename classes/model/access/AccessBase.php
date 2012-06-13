<?php
	namespace model\access;
	abstract class AccessBase extends \backbone\Config{
		protected $db;

		protected $accessors;

		public function __construct( $db = null ){
			parent::__construct();

			if( $db != null ){
				$this->db = $db;
			}
			else{
				$this->db = new \backbone\Database(	
					$this->config['db']['user'],
					$this->config['db']['pass'],
					$this->config['db']['dbname'],
					$this->config['db']['host'],
					'mysql'
				);
			}
		}

		public function genericDelete( $table, $index, $val ){
			// UGLY: Purely in theory, this is SQL Injection Vulnerable
			$sql = "DELETE FROM " . $table . "
					WHERE " . $index . " = " . $val;
			return $this->db->q( $sql );
		}

		protected function sendback($objects){
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
	}