<?php
    namespace business;
    use model;
    use Doctrine\ORM;

    class Authentication{
        protected $em;

        public function __construct( \Doctrine\ORM\EntityManager $em = null ){
            if( $em == null ){
                $docrineFactory = new model\Access();
                $em = $docrineFactory->getEntityManager();
            }

            $this->em = $em;
        }

        public function validateCredentials( $identity, $password ){
            $auth   = $this->em->getRepository('model\entities\Authentication');
            $test   = $auth->findOneBy( array( "identity" => $identity ) );

            if( $test ){
                $match  = $auth->findOneBy( array(
                        "identity" => $identity,
                        "password" => $this->createPassword( $test->getSalt(), $password )
                        )
                    );

                if( $match ){
                    if( !$match->getDisabled() ){
                        return $match->getUser();
                    }
                    else{
                        return false;
                    }
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }

        public function isIdentityFree( $identity ){
            $auth       = $this->em->getRepository('model\entities\Authentication');
            $matches    = $auth->findBy( array(
                    "identity" => $identity
                ) );

            return (count( $matches ) > 0) ? false : true;
        }

        public function generateSalt(){
            return substr( hash( 'whirlpool', rand( 100000000000, 999999999999 ) ), 0, 64 );
        }

        public function createPassword( $salt, $password ){
            return hash( 'whirlpool', $salt . $password );
        }
    }
