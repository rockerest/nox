<?php
	namespace utilities;
	class Converter{
		public static function toArray( $objects ){
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
