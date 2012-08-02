<?php
	namespace model\access;
	use model\objects;
	class RoleAccess extends AccessBase{
		public function __construct( $db = null ){
			parent::__construct( $db );
		}

		public function getById( $id ){
			$sql = "SELECT *
					FROM roles
					WHERE
						roleid = ?";
			$values = array( $id );
			$res = $this->db->qwv($sql, $values);

			return $this->wrap($res);
		}

		public function getByName( $name ){
			$sql = "SELECT *
					FROM roles
					WHERE
						name LIKE '%?%'";
			$values = array( $name );
			$res = $this->db->qwv($sql, $values);

			return $this->wrap($res);
		}

		public function getAll(){
			$sql = "SELECT *
					FROM roles";
			$res = $this->db->q( $sql );

			return $this->wrap( $res );
		}

		public function add($name, $description){
			$role = new objects\Role(null, $description, $name, $this );
			$res = $role->save();

			if( $res ){
				return $res;
			}
			else{
				return false;
			}
		}

		public function save( $obj ){
			if( !$obj->getRoleId() ){
				$sql = "INSERT INTO roles (
							description,
							name
						)
						VALUES ( ?, ? )";
				$values = array(
							$obj->getDescription(),
							$obj->getName()
						);
				$this->db->qwv($sql, $values);

				if( $this->db->stat() ){
					return $obj->setRoleId( $this->db->last() );
				}
				else{
					return false;
				}
			}
			else{
				$sql = "UPDATE roles
						SET
							description = ?,
							name = ?
						WHERE
							roleid = ?";
				$values = array(
							$obj->getDescription(),
							$obj->getName(),
							$obj->getRoleId()
						);
				$this->db->qwv($sql, $values);

				return $this->db->stat();
			}
		}

		public function delete( $obj ){
			//don't allow a role to be deleted if users are still assigned that role
			$auth = new AuthenticationAccess( $this->db );
			if( !$auth->getByRoleId( $obj->getRoleId() ) ){
				return $this->genericDelete( 'roles', 'roleid', $obj->getRoleId() );
			}
			else{
				return false;
			}
		}

		public function wrap($roles){
			$roleList = array();
			foreach( $roles as $role ){
				array_push(
					$roleList,
					new objects\Role(
						$role['roleid'],
						$role['description'],
						$role['name'],
						$this
					)
				);
			}

			return $this->sendback($roleList);
		}
	}
