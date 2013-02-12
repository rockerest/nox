<?php
    namespace model\entities;
    use Doctrine\ORM\EntityRepository;

    /**
     * @Entity
     * @Table(name="quick_logins")
     */
    class QuickLogin{
        /**
         * @Id
         * @Column(type="integer") @GeneratedValue
         */
        private $id;

        /**
         * @Column(type="string", length=128)
         */
        private $hash;

        /**
         * @Column(type="datetime")
         */
        private $expires;

        /**
         * @Column(type="boolean")
         */
        private $used;

        /**
         * @ManyToOne(targetEntity="User")
         * @JoinColumn(name="userid", referencedColumnName="id")
         */
        private $user;

        /**
         * GETTERS
         */

        public function getId(){
            return $this->id;
        }

        public function getHash(){
            return $this->hash;
        }

        public function getExpires(){
            return $this->expires;
        }

        public function getUsed(){
            return $this->used;
        }

        public function getUser(){
            return $this->user;
        }

        /**
         * SETTERS
         */

        public function setHash( $hash ){
            $this->hash = $hash;
        }

        public function setExpires( $time ){
            $this->expires = $time instanceof \DateTime ? $time : new DateTime( $time );
        }

        public function setUsed( $used ){
            $this->used;
        }

        public function setUser( $user ){
            $this->user;
        }
    }
