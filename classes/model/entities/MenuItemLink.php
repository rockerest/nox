<?php
    namespace model\entities;
    use Doctrine\ORM\EntityRepository;

    /**
     * @entity
     * @Table(name="menus_have_menu_items")
     */
    class MenuItemLink{
        /**
         * @Id
         * @Column(type="integer") @GeneratedValue
         */
        private $id;

        /**
         * @Column(type="integer")
         */
        private $order;

        /**
         * @ManyToOne(targetEntity="Menu", inversedBy="links")
         * @JoinColumn(name="menuid", referencedColumnName="id")
         */
        private $menu;

        /**
         * @ManyToOne(targetEntity="MenuItem", inversedBy="links")
         * @JoinColumn(name="menu_itemid", referencedColumnName="id")
         */
        private $item;

        /**
         * GETTERS
         */

        public function getId(){
            return $this->id;
        }

        public function getOrder(){
            return $this->order;
        }

        public function getMenu(){
            return $this->menu;
        }

        public function getItem(){
            return $this->item;
        }

        /**
         * SETTERS
         */

        public function setOrder( $order ){
            $this->order = $order;
        }

        public function setMenu( $menu ){
            $this->menu = $menu;
        }

        public function setItem( $item ){
            $this->item = $item;
        }
    }
