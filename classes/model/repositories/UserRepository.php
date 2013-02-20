<?php
    namespace model\repositories;
    use Doctrine\ORM\EntityRepository;
    use Doctrine\ORM\Query\Expr;

    class UserRepository extends EntityRepository{
        public function search( $term ){
            if( !$term ){
                return $this
                        ->_em
                        ->createQuery('SELECT u FROM model\entities\User u')
                        ->addOrderBy( 'u.lname' )
                        ->addOrderBy( 'u.fname' )
                        ->getResult();
            }
            else{
                $qb     = $this->_em->createQueryBuilder();

                $qb ->select(array('u'))
                    ->from('model\entities\User', 'u')
                    ->where( $qb->expr()->like( 'u.lname', '?1' ) )
                    ->orWhere( $qb->expr()->like( 'u.fname', '?2' ) )
                    ->orWhere( $qb->expr()->like( 'CONCAT(u.fname, CONCAT(\' \', u.lname))', '?3' ) )
                    ->addOrderBy( 'u.lname' )
                    ->addOrderBy( 'u.fname' )
                    ->setParameters(
                        array(
                            1 => "%" . $term . "%",
                            2 => "%" . $term . "%",
                            3 => "%" . $term . "%"
                        )
                    );

                $query = $qb->getQuery();
                return $query->getResult();
            }
        }

        public function getAllWithRestrictions(){
            $qb     = $this->_em->createQueryBuilder();

            $qb ->select(array('u'))
                ->from('model\entities\User', 'u')
                ->leftJoin('u.authentication', 'a')
                ->add('where', $qb->expr()->orx(
                   $qb->expr()->eq('a.disabled', '1'),
                   $qb->expr()->eq('a.resetPassword', '1')
               ));

            $query = $qb->getQuery();
            return $query->getResult();
        }
    }
