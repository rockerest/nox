<?php
	namespace model\access;
	use model\objects;
	class ContactAccess extends AccessBase{
		protected $tableName;

		public function __construct( $db = null ){
			parent::__construct( $db );

			$this->tableName = $this->uiPre . "contacts";
		}

		public function getById( $id ){
			$sql = "SELECT *
					FROM
						" . $this->tableName . "
					WHERE
						contactid = ?";
			$values = array( $id );
			$res = $this->db->qwv( $sql, $values );

			return $this->wrap( $res );
		}

		public function getByUserId( $id ){
			$sql = "SELECT *
					FROM
						" . $this->tableName . "
					WHERE
						userid = ?";
			$values = array($id);
			$res = $this->db->qwv( $sql, $values );

			return $this->wrap( $res );
		}

		public function add($userid, $email, $phone = null){
			$userDA = new UserAccess( $this->db );

			$contact = new objects\Contact(null, $userid, $phone, $email, $this, $userDA );
			$res = $contact->save();

			if( $res ){
				return $res;
			}
			else{
				return false;
			}
		}

		public function save( $obj ){
			if( !$obj->getContactId() ){
				$sql = "INSERT INTO " . $this->tableName . " (
							userid,
							phone,
							email
						)
						VALUES ( ?, ?, ? )";
				$values = array(
							$obj->getUserId(),
							$obj->getPhone(),
							$obj->getEmail()
						);
				$this->db->qwv($sql, $values);

				if( $this->db->stat() ){
					return $obj->setContactId( $this->db->last() );
				}
				else{
					return false;
				}
			}
			else{
				$sql = "UPDATE " . $this->tableName . "
						SET
							userid = ?,
							phone = ?,
							email = ?
						WHERE
							contactid = ?";
				$values = array(
							$obj->getUserId(),
							$obj->getPhone(),
							$obj->getEmail(),
							$obj->getContactId()
						);
				$this->db->qwv($sql, $values);

				return $this->db->stat();
			}
		}

		public function delete( $obj ){
			return $this->genericDelete( $this->tableName, 'contactid', $obj->getContactId() );
		}

		public function wrap($contacts){
			$contactList = array();
			foreach( $contacts as $contact ){
				array_push(
					$contactList,
					new objects\Contact(
						$contact['contactid'],
						$contact['userid'],
						$contact['phone'],
						$contact['email'],
						$this,
						new UserAccess( $this->db )
					)
				);
			}

			return $this->sendback($contactList);
		}
	}
