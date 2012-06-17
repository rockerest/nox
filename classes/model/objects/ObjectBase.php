<?php
	namespace model\objects;
	abstract class ObjectBase extends \backbone\Config{
		protected $accessors;

		public function __construct(){
			parent::__construct();
		}

		// Data throughput
		public function save(){
			return $this->accessors['Main']->save( $this );
		}

		public function delete(){
			return $this->accessors['Main']->delete( $this );
		}
	}
?>
