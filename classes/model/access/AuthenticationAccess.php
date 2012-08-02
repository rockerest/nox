<?php
	namespace model\access;
	use model\objects;
	class AuthenticationAccess extends AccessBase{
		public function __construct( $db = null ){
			parent::__construct( $db );
		}

		public function validate( $identity, $password ){
			//Select the salt for the attempted user identity
			$sql = "SELECT
						salt
					FROM
						authentications
					WHERE
						identity = ?";
			$values = array( $identity );
			$res = $this->db->qwv( $sql, $values );

			// Created a salted password from the attempted password
			// Will created a bad salt if the identity doesn't exist
			$salted = hash( 'whirlpool', $res[0]['salt'] . $password );

			// Get the user that matches the correct identity AND salted, hashed password
			$sql = "SELECT *
					FROM
						authentications
					WHERE
						identity = ?
						AND password = ?";
			$values = array( $identity, $salted );
			$res = $this->db->qwv( $sql, $values );

			if( count($res) != 1 ){
				return false;
			}
			else{
				$auth = $this->getById( $res[0]['authenticationid'] );
				$user = $auth->getUser();

				if( !$auth->getDisabled() ){
					return $user;
				}
				else{
					return false;
				}
			}
		}

		public function checkIdentity( $identity ){
			$sql = "SELECT
						authenticationid
					FROM
						authentications
					WHERE
						identity = ?";
			$values = array( $identity );
			$res = $this->db->qwv( $sql, $values );

			return count( $res );
		}

		public function getById( $id ){
			$sql = "SELECT *
					FROM
						authentications
					WHERE
						authenticationid = ?";
			$values = array( $id );
			$res = $this->db->qwv( $sql, $values );

			return $this->wrap( $res );
		}

		public function getByIdentity( $identity ){
			$sql = "SELECT *
					FROM
						authentications
					WHERE
						identity = ?";
			$values = array( $identity );
			$res = $this->db->qwv( $sql, $values );

			return $this->wrap( $res );
		}

		public function getByUserId( $id ){
			$sql = "SELECT *
					FROM
						authentications
					WHERE
						userid = ?";
			$values = array( $id );
			$res = $this->db->qwv( $sql, $values );

			return $this->wrap($res);
		}

		public function add( $userid, $identity, $pass, $roleid ){
			$roleDA = new RoleAccess( $this->db );
			$userDA = new UserAccess( $this->db );

			$vals = $this->hash( $pass );
			$authentication = new objects\Authentication(null, $userid, $roleid, $identity, $vals[1], $vals[0], 0, 0, $this, $roleDA, $userDA);
			$res = $authentication->save();

			if( $res ){
				return $res;
			}
			else{
				return false;
			}
		}

		public function save( $obj ){
			if( !$obj->getAuthenticationId() ){
				if( $obj->allSet() ){
					$sql = "INSERT INTO authentications (
								userid,
								roleid,
								identity,
								password,
								salt,
								resetPassword,
								disabled
							)
							VALUES ( ?, ?, ?, ?, ?, ?, ? )";
					$values = array(
								$obj->getUserId(),
								$obj->getRoleId(),
								$obj->getIdentity(),
								$obj->getPassword(),
								$obj->getSalt(),
								$obj->getResetPassword(),
								$obj->getDisabled()
							);
					$this->db->qwv($sql, $values);

					if( $this->db->stat() ){
						return $obj->setAuthenticationId( $this->db->last() );
					}
					else{
						return false;
					}
				}
				else{
					return false;
				}
			}
			else{
				if( $obj->allSet() ){
					$sql = "UPDATE authentications
							SET
								userid = ?,
								roleid = ?,
								identity = ?,
								password = ?,
								salt = ?,
								resetPassword = ?,
								disabled = ?
							WHERE
								authenticationid = ?";
					$values = array(
								$obj->getUserId(),
								$obj->getRoleId(),
								$obj->getIdentity(),
								$obj->getPassword(),
								$obj->getSalt(),
								$obj->getResetPassword(),
								$obj->getDisabled(),
								$obj->getAuthenticationId()
							);
					$this->db->qwv($sql, $values);

					return $this->db->stat();
				}
				else{
					return false;
				}
			}
		}

		public function wrap($authentications){
			$authenticationList = array();
			foreach( $authentications as $authentication ){
				array_push(
					$authenticationList,
					new objects\Authentication(
						$authentication['authenticationid'],
						$authentication['userid'],
						$authentication['roleid'],
						$authentication['identity'],
						$authentication['password'],
						$authentication['salt'],
						$authentication['resetPassword'],
						$authentication['disabled'],
						$this,
						new RoleAccess( $this->db ),
						new UserAccess( $this->db )
					)
				);
			}

			return $this->sendback($authenticationList);
		}

		public function hash( $pass ){
			$salt = substr( hash( 'whirlpool', rand( 100000000000, 999999999999 ) ), 0, 64 );
			$real_pass = hash( 'whirlpool', $salt . $pass );

			return array( $salt, $real_pass );
		}
	}
