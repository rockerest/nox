<?php
    namespace model\entities;

    /**
     * @Entity(repositoryClass="model\repositories\UserRepository")
     * @Table(name="users")
     */
    class User{
        /**
         * @Id
         * @Column(type="integer") @GeneratedValue
         */
        private $id;

        /**
         * @Column(type="string", length=64)
         */
        private $fname;

        /**
         * @Column(type="string", length=64)
         */
        private $lname;

        /**
         * @Column(type="string", length=1)
         */
        private $gender;

        /**
         * @OneToOne(targetEntity="Authentication", mappedBy="user", cascade="persist")
         */
        private $authentication;

        /**
         * @OneToMany(targetEntity="Contact", mappedBy="user", cascade="persist")
         */
        private $contacts;

        public function __construct() {
            $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * GETTERS
         */

        public function getId(){
            return $this->id;
        }

        public function getFname(){
            return $this->fname;
        }

        public function getLname(){
            return $this->lname;
        }

        public function getFullName(){
            return $this->getFname() . " " . $this->getLname();
        }

        public function getGender(){
            return $this->gender;
        }

        public function getAuthentication(){
            return $this->authentication;
        }

        public function getContacts(){
            return $this->contacts;
        }

        /**
         * SETTERS
         */

        public function setFname( $fname ){
            $this->fname = $fname;
            return $this;
        }

        public function setLname( $lname ){
            $this->lname = $lname;
            return $this;
        }

        public function setGender( $gender ){
            $this->gender = $gender;
            return $this;
        }

        public function setAuthentication( $authentication ){
            $this->authentication = $authentication;
            return $this;
        }

        public function setContacts( $contacts ){
            $this->contacts = $contacts;
            return $this;
        }
    }
