<?php
    namespace model\access;
    use model\objects;
    class Menu_ItemAccess extends AccessBase{
        protected   $tableName;

        public function __construct( $db = null ){
            parent::__construct( $db );

            $this->tableName = $this->uiPre . "menu_items";
            $this->active = true;
        }

        public function getById( $id ){
            $sql = "SELECT *
                    FROM
                        " . $this->tableName . "
                    WHERE
                        menu_itemid = ?";
            $values = array( $id );
            $res = $this->db->qwv($sql, $values);

            return $this->wrap($res);
        }

        public function getByMenuId( $menuid ){
            $sql = "SELECT
                        " . $this->tableName . ".menu_itemid,
                        `order`,
                        display,
                        href,
                        icon
                    FROM
                        " . $this->uiPre . "menus_have_menu_items,
                        " . $this->tableName . "
                    WHERE
                        menus_have_menu_items.menuid = ?
                        AND menus_have_menu_items.menu_itemid = " . $this->tableName . ".menu_itemid
                    ORDER BY
                        `order` ASC";
            $values = array( $menuid );
            $res = $this->db->qwv( $sql, $values );

            return $this->wrap( $res );
        }

        public function add( $display, $href, $icon ){
            $menu = new objects\Menu_Item(null, $display, $href, $icon, $this );
            $res = $menu->save();

            if( $res ){
                return $res;
            }
            else{
                return false;
            }
        }

        public function save( $obj ){
            if( !$obj->getMenu_ItemId() ){
                $sql = "INSERT INTO " . $this->tableName . " (
                            display,
                            href,
                            icon
                        )
                        VALUES ( ?, ?, ? )";
                $values = array(
                            $obj->getDisplay(),
                            $obj->getHref(),
                            $obj->getIcon()
                        );
                $this->db->qwv($sql, $values);

                if( $this->db->stat() ){
                    return $obj->setMenu_ItemId( $this->db->last() );
                }
                else{
                    return false;
                }
            }
            else{
                $sql = "UPDATE
                            " . $this->tableName . "
                        SET
                            display = ?,
                            href = ?,
                            icon = ?
                        WHERE
                            menu_itemid = ?";
                $values = array(
                            $obj->getDisplay(),
                            $obj->getHref(),
                            $obj->getIcon(),
                            $obj->getMenu_ItemId()
                        );
                $this->db->qwv($sql, $values);

                return $this->db->stat();
            }
        }

        public function delete( $obj ){
            return $this->genericDelete( $this->tableName, 'menu_itemid', $obj->getMenuId() );
        }

        public function wrap( $items ){
            $list = array();
            foreach( $items as $i ){
                array_push(
                    $list,
                    new objects\Menu_Item(
                        $i['menu_itemid'],
                        $i['display'],
                        $i['href'],
                        $i['icon'],
                        $this
                    )
                );
            }

            return $this->sendback($list);
        }
    }
