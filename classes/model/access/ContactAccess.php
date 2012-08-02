<?php
	namespace model\access;
	use model\objects;
	class ContactAccess extends AccessBase{
		public function __construct( $db = null ){
			parent::__construct( $db );
		}

		public function getById( $id ){
			$sql = "SELECT *
					FROM
						contacts
					WHERE
						contactid = ?";
			$values = array( $id );
			$res = $this->db->qwv( $sql, $values );

			return $this->wrap( $res );
		}

		public function getByUserId( $id ){
			$sql = "SELECT *
					FROM
						contacts
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
				$sql = "INSERT INTO contacts (
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
				$sql = "UPDATE contacts
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
			return $this->genericDelete( 'contacts', 'contactid', $obj->getContactId() );
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
