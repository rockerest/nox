<?php
	namespace business;
	use model;
	use Doctrine\ORM;

	class Permission{
		protected $em;

        public function __construct( Doctrine\ORM\EntityManager $em = null ){
            if( $em == null ){
                $docrineFactory = new model\Access();
                $em = $docrineFactory->getEntityManager();
            }

            $this->em = $em;
        }

		public function elevateRole( $user, $them ){
			if( $user && $them ){
				$tRi = $them->getAuthentication()->getRole()->getId();

				if( ($tRi > $user->getAuthentication()->getRole()->getId()) && ($tRi > 1) ){
					return true;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}

		public function reduceRole( $user, $them ){
			if( $user && $them ){
				$tRi = $them->getAuthentication()->getRole()->getId();
				$authRepo = $this->em->getRepository('model\entities\Authentication');

				$auths = $authRepo->findBy( array(	"roleid" => $tRi,
													"disabled" => false ) );

				if( ($tRi >= $user->getAuthentication()->getRole()->getId()) && (count( $auths ) > 1) ){
					return true;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}

		public function impersonate( $user, $them ){
			$uRi = $user->getAuthentication()->getRole()->getId();
			if( $uRi > $them->getAuthentication()->getRole()->getId() || $uRi == 1 ){
				return true;
			}
			else{
				return false;
			}
		}

		public function viewMenu( $menu, $user = null ){
			if( !is_null( $user ) ){
				$uRi = $user->getAuthentication()->getRole()->getId();

				if( $menu->getAuthenticated() ){
					return ( $menu->getRole()->getId() == $uRi );
				}
				else{
					return false;
				}
			}
			else{
				return ( $menu->getAuthenticated() == 0 );
			}
		}
	}
