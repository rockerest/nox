<?php
    namespace model\access;
    use model\objects;
    class MenuAccess extends AccessBase{
        protected   $tableName,
                    $active;

        public function __construct( $db = null ){
            parent::__construct( $db );

            $this->tableName = $this->uiPre . "menus";
            $this->active = true;
        }

        public function filterByActive( $bool = true ){
            $this->active = $bool;
        }

        public function getById( $id ){
            $sql = $this->filter("SELECT *
                    FROM
                        " . $this->tableName . "
                    WHERE
                        menuid = ?");
            $values = array( $id );
            $res = $this->db->qwv($sql, $values);

            return $this->wrap($res);
        }

        public function getByRoleId( $rid ){
            $sql = $this->filter("SELECT *
                    FROM
                        " . $this->tableName . "
                    WHERE
                        roleid = ?");
            $values = array( $rid );
            $res = $this->db->qwv( $sql, $values );

            return $this->wrap( $res );
        }

        public function getByLocation( $loc ){
            $sql = $this->filter("SELECT *
                    FROM
                        " . $this->tableName . "
                    WHERE
                        location = ?");
            $values = array( $loc );
            $res = $this->db->qwv( $sql, $values );

            return $this->wrap( $res );
        }

        public function getAll(){
            $sql = $this->filter("SELECT *
                    FROM
                        " . $this->tableName);
            $res = $this->db->q( $sql );

            return $this->wrap( $res );
        }

        public function search( $title ){
            $sql = $this->filter("SELECT *
                    FROM
                        " . $this->tableName . "
                    WHERE
                        ( title LIKE '%?%'
                        OR location LIKE '%?%' )");
            $values = array( $name );
            $res = $this->db->qwv($sql, $values);

            return $this->wrap($res);
        }

        public function add( $roleid, $title, $loc, $active = 1, $auth = 1 ){
            $menu = new objects\Menu(null, $roleid, $title, $loc, $active, $auth, $this );
            $res = $menu->save();

            if( $res ){
                return $res;
            }
            else{
                return false;
            }
        }

        public function save( $obj ){
            if( !$obj->getMenuId() ){
                $sql = "INSERT INTO " . $this->tableName . " (
                            roleid,
                            title,
                            location,
                            active,
                            authenticated
                        )
                        VALUES ( ?, ?, ? )";
                $values = array(
                            $obj->getRoleId(),
                            $obj->getTitle(),
                            $obj->getLocation(),
                            $obj->getActive(),
                            $obj->getAuthenticated()
                        );
                $this->db->qwv($sql, $values);

                if( $this->db->stat() ){
                    return $obj->setMenuId( $this->db->last() );
                }
                else{
                    return false;
                }
            }
            else{
                $sql = "UPDATE
                            " . $this->tableName . "
                        SET
                            roleid = ?,
                            title = ?,
                            location = ?,
                            active = ?,
                            authenticated = ?
                        WHERE
                            menuid = ?";
                $values = array(
                            $obj->getRoleId(),
                            $obj->getTitle(),
                            $obj->getLocation(),
                            $obj->getActive(),
                            $obj->getAuthenticated(),
                            $obj->getMenuId()
                        );
                $this->db->qwv($sql, $values);

                return $this->db->stat();
            }
        }

        public function delete( $obj ){
            return $this->genericDelete( $this->tableName, 'menuid', $obj->getMenuId() );
        }

        public function wrap( $items ){
            $list = array();
            foreach( $items as $i ){
                array_push(
                    $roleList,
                    new objects\Menu(
                        $i['menuid'],
                        $i['roleid'],
                        $i['title'],
                        $i['location'],
                        $i['active'],
                        $i['authenticated'],
                        $this
                    )
                );
            }

            return $this->sendback($list);
        }

        private function filter( $sql ){
            if( $this->active ){
                return $sql . " AND active = 1";
            }
            else{
                return $sql;
            }
        }
    }
