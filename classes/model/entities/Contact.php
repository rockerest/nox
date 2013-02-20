<?php
    namespace model\entities;
    use Doctrine\ORM\EntityRepository;

    /**
     * @Entity
     * @Table(name="contacts")
     */
    class Contact{
        /**
         * @Id
         * @Column(type="integer") @GeneratedValue
         */
        private $id;

        /**
         * @Column(type="string", length=16)
         */
        private $phone;

        /**
         * @Column(type="string", length=128)
         */
        private $email;

        /**
         * @ManyToOne(targetEntity="User", inversedBy="contacts")
         * @JoinColumn(name="userid", referencedColumnName="id")
         */
        private $user;

        /**
         * GETTERS
         */

        public function getId(){
            return $this->id;
        }

        public function getPhone(){
            return $this->phone;
        }

        public function getEmail(){
            return $this->email;
        }

        public function getFriendlyPhone(){
            $phone = $this->getPhone();
            $phlen = strlen($phone);
            if( $phlen == 10 || $phlen == 11 ){
                //assume US
                if( $phlen == 10 ){
                    $pretty = substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6, 4);
                }
                elseif( $phlen == 11 ){
                    $pretty = '+' . substr($phone, 0, 1) . '-' . substr($phone, 1, 3) . '-' . substr($phone, 4, 3) . '-' . substr($phone, 7, 4);
                }

                return $pretty;
            }
            else{
                //assume International
                return $phone;
            }
        }

        /**
         * SETTERS
         */

        public function setPhone( $phone ){
            $this->phone = $phone;
            return $this;
        }

        public function setEmail( $email ){
            $this->email = $email;
            return $this;
        }

        public function setUser( $user ){
            $this->user = $user;
            return $this;
        }
    }
