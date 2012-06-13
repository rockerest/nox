<?php
	namespace model\access;
	class UserAccess extends \model\access\AccessBase{
		public function __construct( $db = null ){
			parent::__construct( $db );
		}

		public function getById( $id ){
			$sql = "SELECT *
					FROM users
					WHERE
						userid = ?";
			$values = array( $id );
			$res = $this->db->qwv($sql, $values);

			return $this->wrap($res);
		}

		public function search( $term = null ){
			if( !$term ){
				$sql = "SELECT *
						FROM
							users
						ORDER BY
							lname ASC,
							fname ASC";
				$res = $this->db->q($sql);
			}
			else{
				$sql = "SELECT *
						FROM
							users
						WHERE
							lname LIKE ?
							OR fname LIKE ?
							OR CONCAT(fname, ' ', lname) LIKE ?
						ORDER BY
							lname ASC,
							fname ASC";
				$values = array( '%' . $term . '%', '%' . $term . '%', '%' . $term . '%' );
				$res = $this->db->qwv( $sql, $values );
			}

			return $this->wrap( $res );
		}

		public function getAllWithRestrictions(){
			$sql = "SELECT *
					FROM
						users
					WHERE
						userid IN
						(
							SELECT
								userid
							FROM
								authentication
							WHERE
								resetPassword = 1
								OR disabled = 1
						)";
			$res = $this->db->q( $sql );

			return $this->wrap($res);
		}

		public function getAll(){
			return $this->search();
		}

		public function add( $fname, $lname, $identity, $pass, $roleid ){
			$authDA = new \model\access\AuthenticationAccess( $this->db );
			$contDA = new \model\access\ContactAccess( $this->db );

			$taken = $authDA->checkIdentity( $identity );

			if( !$taken ){
				// If the identity is unique, add a new user
				$user = new \model\objects\User(null, $fname, $lname, null );
				$res = $user->save();

				if( $res ){
					// if the new user was successfully added, insert authentication and contact info for that user
					$auth = $authDA->add( $res->getUserId(), $identity, $pass, $roleid );
					$cont = $contDA->add( $res->getUserId(), $identity );

					if( $auth && $cont ){
						// if both the authentication and the contact were added successfully, return the user
						return $res;
					}
					else{
						// attempt to delete the new user
						if( $res->delete() ){
							return false;
						}
						else{
							//Something is very broken
						}
					}
				}
			}
			else{
				return false;
			}
			

			if( $res ){
				return $res;
			}
			else{
				return false;
			}
		}

		public function delete( $obj ){
			// Cross your fingers and hope the CASCADE DELETE takes care of the rest
			return $this->genericDelete( 'users', 'userid', $obj->getUserId() );
		}

		public function save( $obj ){
			if( !$obj->getUserId() ){
				$sql = "INSERT INTO users (
							fname,
							lname,
							gender
						)
						VALUES ( ?, ?, ? )";
				$values = array(
							$obj->getFname(),
							$obj->getLname(),
							$obj->getGender()
						);
				$this->db->qwv($sql, $values);

				if( $this->db->stat() ){
					return $obj->setUserId( $this->db->last() );
				}
				else{
					return false;
				}
			}
			else{
				$sql = "UPDATE users
						SET
							fname = ?,
							lname = ?,
							gender = ?
						WHERE
							userid = ?";
				$values = array(
							$obj->getFname(),
							$obj->getLname(),
							$obj->getGender(),
							$obj->getUserId()
						);
				$this->db->qwv($sql, $values);

				return $this->db->stat();
			}
		}

		public function wrap($users){
			$userList = array();
			foreach( $users as $user ){
				array_push(
					$userList,
					new \model\objects\User(
						$user['userid'],
						$user['fname'],
						$user['lname'],
						$user['gender'],
						$this,
						new \model\access\AuthenticationAccess( $this->db ),
						new \model\access\ContactAccess( $this->db )
					)
				);
			}

			return $this->sendback($userList);
		}
	}