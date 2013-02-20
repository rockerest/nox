<?php
    namespace model;

    use Doctrine\ORM\Tools\Setup;
    use Doctrine\ORM\EntityManager;

    class Access extends \backbone\Config{
        protected   $em;

        public function __construct( $setup = "default" ){
            parent::__construct();

            $paths = array(
                "/classes/model/entities"
            );

            if( $setup == "default" ){
                $dbParams = array(
                    'driver'    => 'pdo_mysql',
                    'user'      => $this->config->db->user,
                    'password'  => $this->config->db->pass,
                    'dbname'    => $this->config->db->dbname
                );

                $devMode = false;
            }

            $setup = Setup::createAnnotationMetadataConfiguration( $paths, $devMode );
            $this->em = EntityManager::create( $dbParams, $setup );
        }

        public function getEntityManager(){
            return $this->em;
        }

        public function persistFlushRefresh( $entity ){
            $this->em->persist( $entity );
            $this->em->flush();
            $this->em->refresh( $entity );

            return $entity;
        }
    }
?>
