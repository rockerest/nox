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

		public static function toObject( $item, $class ){
			if( $item instanceof $class ){
				return $item;
			}
			elseif( is_integer( $item ) ){
				//attempt to get the object with that ID
				$location = preg_split('#[\/]#', $class);
				// The class name should be the last one
				$clsnm = end( $location );
				// the data access class should be located at "model\access\[TheClassName]Access"
				$path = 'model\access\\' . $clsnm . 'Access';
				$accessor = new $path;
				// the accessor should have a function called "getById"
				$realCls = $accessor->getById( $item );

				if( $realCls instanceof $class ){
					return $realCls;
				}
				else{
					// Well, looks like we really screwed this one up.
					return false;
				}
			}
			else{
				return false;
			}
		}
	}
