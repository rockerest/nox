<?php
	namespace model\access;
	use model\objects;
	class Quick_LoginAccess extends AccessBase{
		protected $tableName;

		public function __construct( $db = null ){
			parent::__construct( $db );

			$this->tableName = $this->uiPre . "quick_logins";
		}

		public function getById( $id ){
			$sql = "SELECT *
					FROM
						" . $this->tableName . "
					WHERE
						quick_loginid = ?";
			$values = array( $id );
			$ql = $this->db->qwv($sql, $values);

			return $this->wrap($ql);
		}

		public function getByHash( $hash ){
			$sql = "SELECT *
					FROM
						" . $this->tableName . "
					WHERE
						hash = ?
						AND used = 0
						AND expires > ?";
			$values = array(
						$hash,
						time()
					);
			$ql = $this->db->qwv($sql, $values);

			return $this->wrap($ql);
		}

		public function add($hash, $userid, $expires, $used){
			$userDA = new UserAccess( $this->db );

			$ql = new objects\Quick_Login( null, $hash, $userid, $expires, $used, $this, $userDA );
			$res = $ql->save();

			if( $res ){
				return $res;
			}
			else{
				return false;
			}
		}

		public function save( $obj ){
			if( !$obj->getQuick_LoginId() ){
				$sql = "INSERT INTO " . $this->tableName . " (
							hash,
							userid,
							expires,
							used
						)
						VALUES ( ?, ?, ?, ? )";
				$values = array(
							$obj->getHash(),
							$obj->getUserId(),
							$obj->getExpires(),
							$obj->getUsed()
						);
				$this->db->qwv($sql, $values);

				if( $this->db->stat() ){
					return $obj->setQuick_LoginId( $this->db->last() );
				}
				else{
					return false;
				}
			}
			else{
				$sql = "UPDATE " . $this->tableName . "
						SET
							hash = ?,
							userid = ?,
							expires = ?,
							used = ?
						WHERE
							quick_loginid = ?";
				$values = array(
							$obj->getHash(),
							$obj->getUserId(),
							$obj->getExpires(),
							$obj->getUsed(),
							$obj->getQuick_LoginId()
						);
				$this->db->qwv($sql, $values);

				return $this->db->stat();
			}
		}

		public function delete( $obj ){
			return $this->genericDelete( $this->tableName, 'quick_loginid', $obj->getQuick_LoginId() );
		}

		public function wrap($qls){
			$qlList = array();
			foreach( $qls as $ql ){
				array_push(
					$qlList,
					new objects\Quick_Login(
						$ql['quick_loginid'],
						$ql['hash'],
						$ql['userid'],
						$ql['expires'],
						$ql['used'],
						$this,
						new UserAccess( $this->db )
					)
				);
			}

			return $this->sendback($qlList);
		}
	}
