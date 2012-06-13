<?php
	namespace model\access;
	class Quick_LoginAccess extends \model\access\AccessBase{
		public function __construct( $db = null ){
			parent::__construct( $db );
		}

		public function getById( $id ){
			$sql = "SELECT *
					FROM quick_logins
					WHERE
						quick_loginid = ?";
			$values = array( $id );
			$ql = $this->db->qwv($sql, $values);

			return $this->wrap($ql);
		}

		public function getByHash( $hash ){
			$sql = "SELECT *
					FROM quick_logins
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
			$ql = new \model\objects\Quick_Login(null, $hash, $userid, $expires, $used);
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
				$sql = "INSERT INTO quick_logins (
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
				$sql = "UPDATE quick_logins
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
			return $this->genericDelete( 'quick_logins', 'quick_loginid', $obj->getQuick_LoginId() );
		}

		public function wrap($qls){
			$qlList = array();
			foreach( $qls as $ql ){
				array_push(
					$qlList,
					new \model\objects\Quick_Login(
						$ql['quick_loginid'],
						$ql['hash'],
						$ql['userid'],
						$ql['expires'],
						$ql['used'],
						$this,
						new \model\access\UserAccess( $this->db )
					)
				);
			}

			return $this->sendback($qlList);
		}
	}