<?php
    namespace model\entities;
    use Doctrine\ORM\EntityRepository;

    /**
     * @Entity
     * @Table(name="menu_items")
     */
    class MenuItem{
        /**
         * @Id
         * @Column(type="integer") @GeneratedValue
         */
        private $id;

        /**
         * @Column(type="string", length=64)
         */
        private $display;

        /**
         * @Column(type="string", length=256)
         */
        private $href;

        /**
         * @Column(type="string", length=256)
         */
        private $icon;

        /**
         * @OneToMany(targetEntity="MenuItemLink", mappedBy="item")
         **/
        private $links;

        public function __construct(){
            $this->links = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * GETTERS
         */

        public function getId(){
            return $this->id;
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

        public function getLinks(){
            return $this->links;
        }

        /**
         * SETTERS
         */

        public function setDisplay( $display ){
            $this->display = $display;
        }

        public function setHref( $href ){
            $this->href = $href;
        }

        public function setIcon( $icon ){
            $this->icon = $icon;
        }

        public function setLinks( $links ){
            $this->links = $links;
        }
    }
