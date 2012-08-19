<?php
	namespace business;
	use utilities;
	use model\access;
	class Permission{
		public function elevateRole( $user, $them ){
			$authDA = new access\AuthenticationAccess;

			if( ( $user = utilities\Converter::toObject( $user, 'model\\objects\\User' ) ) && ( $them = utilities\Converter::toObject( $them, 'model\\objects\\User' ) ) ){
				$tRi = $them->getAuthentication()->getRoleId();

				if( ($tRi > $user->getAuthentication()->getRoleId()) && ($tRi > 1) ){
					return true;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}

		public function reduceRole( $user, $them ){
			$authDA = new access\AuthenticationAccess;

			if( ( $user = utilities\Converter::toObject( $user, 'model\\objects\\User' ) ) && ( $them = utilities\Converter::toObject( $them, 'model\\objects\\User' ) ) ){
				$tRi = $them->getAuthentication()->getRoleId();
				$usersAtLevel = $authDA->getByRoleId( $tRi );

				if( ($tRi >= $user->getAuthentication()->getRoleId()) && (count( $usersAtLevel ) > 1) ){
					return true;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}

		public function impersonate( $user, $them ){
			$uRi = $user->getAuthentication()->getRoleId();
			if( $uRi > $them->getAuthentication()->getRoleId() || $uRi == 1 ){
				return true;
			}
			else{
				return false;
			}
		}

		public function viewMenu( $menu, $user = null ){
			if( !is_null( $user ) ){
				$uRi = $user->getAuthentication()->getRoleId();

				if( $menu->getAuthenticated() ){
					return ( $menu->getRoleId() == $uRi );
				}
				else{
					return false;
				}
			}
			else{
				return ( $menu->getAuthenticated() == 0 );
			}
		}
	}
