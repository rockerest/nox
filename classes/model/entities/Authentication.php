<?php
    namespace model\entities;

    /**
     * @Entity
     * @Table(name="authentications")
     */
    class Authentication{
        /**
         * @Id
         * @Column(type="integer") @GeneratedValue
         */
        private $id;

        /**
         * @Column(type="string", length=256)
         */
        private $identity;

        /**
         * @Column(type="string", length=128)
         */
        private $password;

        /**
         * @Column(type="string", length=64)
         */
        private $salt;

        /**
         * @Column(type="boolean")
         */
        private $resetPassword;

        /**
         * @Column(type="boolean")
         */
        private $disabled;

        /**
         * @OneToOne(targetEntity="User", inversedBy="authentication", cascade={"persist"})
         * @JoinColumn(name="userid", referencedColumnName="id")
         */
        private $user;

        /**
         * @ManyToOne(targetEntity="Role", cascade={"persist"})
         * @JoinColumn(name="roleid", referencedColumnName="id")
         */
        private $role;

        public function __construct(){
            $this->setDisabled( false );
            $this->setResetPassword( false );
        }

        /**
         * GETTERS
         */

        public function getId(){
            return $this->id;
        }

        public function getIdentity(){
            return $this->identity;
        }

        public function getPassword(){
            return $this->password;
        }

        public function getSalt(){
            return $this->salt;
        }

        public function getResetPassword(){
            return $this->resetPassword;
        }

        public function getDisabled(){
            return $this->disabled;
        }

        public function getUser(){
            return $this->user;
        }

        public function getRole(){
            return $this->role;
        }

        /**
         * SETTERS
         */

        public function setIdentity( $identity ){
            $this->identity = $identity;
            return $this;
        }

        public function setPassword( $password ){
            $this->password = $password;
            return $this;
        }

        public function setSalt( $salt ){
            $this->salt = $salt;
            return $this;
        }

        public function setResetPassword( $resetPassword ){
            $this->resetPassword = $resetPassword;
            return $this;
        }

        public function setDisabled( $disabled ){
            $this->disabled = $disabled;
            return $this;
        }

        public function setUser( $user ){
            $this->user = $user;
            return $this;
        }

        public function setRole( $role ){
            $this->role = $role;
            return $this;
        }
    }
