<?php
    namespace model\objects;
    use model\access;
    class Menu extends ObjectBase{
        protected   $menuid,
                    $roleid,
                    $title,
                    $location,
                    $active,
                    $authenticated;

        public function __construct($menuid, $roleid, $title, $loc, $active, $auth, $menuDA = null, $miDA = null ){
            parent::__construct();

            $this->menuid = $menuid;
            $this->roleid = $roleid;
            $this->title = $title;
            $this->location = $loc;
            $this->active = $active;
            $this->authenticated = $auth;

            $this->accessors['Main'] = isset($menuDA) ? $menuDA : new access\MenuAccess;
            $this->accessors['Menu_Item'] = isset($miDA) ? $miDA : new access\Menu_ItemAccess;
        }

        // Setters
        public function setMenuId( $id ){
            if( !isset( $this->menuid ) ){
                $this->menuid = $id;
            }

            return $this;
        }

        public function setRoleId( $id ){
            $this->roleid = $id;
            return $this;
        }

        public function setTitle( $title ){
            $this->title = $title;
            return $this;
        }

        public function setLocation( $loc ){
            $this->location = $loc;
            return $this;
        }

        public function setActive( $active ){
            $this->active = $active;
            return $this;
        }

        public function setAuthenticated( $auth ){
            $this->authenticated = $auth;
            return $this;
        }

        // Getters
        public function getMenuId(){
            return $this->menuid;
        }

        public function getRoleId(){
            return $this->roleid;
        }

        public function getTitle(){
            return $this->title;
        }

        public function getLocation(){
            return $this->location;
        }

        public function getActive(){
            return $this->active;
        }

        public function getAuthenticated(){
            return $this->authenticated;
        }

        // Extrapolated Getters
        public function getMenu_Item(){
            return $this->accessors['Menu_Item']->getByMenuId( $this->getMenuId() );
        }
    }
