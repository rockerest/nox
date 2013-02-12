<?php
    namespace model\entities;
    use Doctrine\ORM\EntityRepository;

    /**
     * @entity
     * @Table(name="menus")
     */
    class Menu{
        /**
         * @Id
         * @Column(type="integer") @GeneratedValue
         */
        private $id;

        /**
         * @Column(type="string", length=64)
         */
        private $title;

        /**
         * @Column(type="string", length=64)
         */
        private $location;

        /**
         * @Column(type="boolean")
         */
        private $active;

        /**
         * @Column(type="boolean")
         */
        private $authenticated;

        /**
         * @ManyToOne(targetEntity="Role")
         * @JoinColumn(name="roleid", referencedColumnName="id")
         */
        private $role;

        /**
         * @OneToMany(targetEntity="MenuItemLink", mappedBy="menu")
         **/
        private $links;

        public function __construct(){
            $this->links = new \Doctrine\Common\Collections\ArrayCollection();
            $this->active = 1;
            $this->authenticated = 1;
        }

        /**
         * GETTERS
         */

        public function getId(){
            return $this->id;
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

        public function getRole(){
            return $this->role;
        }

        public function getLinks(){
            return $this->links;
        }

        public function getOrderedLinks(){
            $links = $this->getLinks();
            $iterator = $links->getIterator();

            $iterator->uasort(function( $first, $second ){
                if ($first === $second) {
                    return 0;
                }

                return $first->getOrder() < $second->getOrder() ? -1 : 1;
            });

            return $iterator;
        }

        /**
         * SETTERS
         */

        public function setTitle( $title ){
            $this->title = $title;
        }

        public function setLocation( $location ){
            $this->location = $location;
        }

        public function setActive( $active ){
            $this->active = $active;
        }

        public function setAuthenticated( $authenticated ){
            $this->authenticated = $authenticated;
        }

        public function setRole( $role ){
            $this->role = $role;
        }

        public function setLinks( $links ){
            $this->links = $links;
        }
    }
