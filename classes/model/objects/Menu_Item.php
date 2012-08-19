<?php
    namespace model\objects;
    use model\access;
    class Menu_Item extends ObjectBase{
        protected   $menu_itemid,
                    $display,
                    $href,
                    $icon;

        public function __construct($miid, $disp, $href, $icon, $miDA = null ){
            parent::__construct();

            $this->menu_itemid = $miid;
            $this->display = $disp;
            $this->href = $href;
            $this->icon = $icon;

            $this->accessors['Main'] = isset($miDA) ? $miDA : new access\Menu_ItemAccess;
        }

        // Setters
        public function setMenu_Itemid( $id ){
            if( !isset( $this->menu_itemid ) ){
                $this->menu_itemid = $id;
            }

            return $this;
        }

        public function setDisplay( $display ){
            $this->display = $display;
            return $this;
        }

        public function setHref( $href ){
            $this->href = $href;
            return $this;
        }

        public function setIcon( $icon ){
            $this->icon = $icon;
            return $this;
        }

        // Getters
        public function getMenu_ItemId(){
            return $this->menu_itemid;
        }

        public function getDisplay(){
            return $this->display;
        }

        public function getHref(){
            return $this->href;
        }

        public function getIcon(){
            return $this->icon;
        }
    }
