<?php
	namespace model\access;
	abstract class AccessBase extends \backbone\Config{
		protected 	$db,
					$accessors,
					$uiPre;

		public function __construct( $db = null ){
			parent::__construct();

			if( $db != null ){
				$this->db = $db;
			}
			else{
				$this->db = new \backbone\Database(
					$this->config->db->user,
					$this->config->db->pass,
					$this->config->db->dbname,
					$this->config->db->host,
					$this->config->db->type
				);
			}

			$this->uiPre = $this->config->db->ui->prefix;
		}

		public function genericDelete( $table, $index, $val ){
			// UGLY: Purely in theory, this is SQL Injection Vulnerable
			$sql = "DELETE FROM " . $this->uiPre . $table . "
					WHERE " . $index . " = " . $val;
			$this->db->q( $sql );
			return $this->db->stat();
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
