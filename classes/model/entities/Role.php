<?php
    namespace model\entities;
    use Doctrine\ORM\EntityRepository;

    /**
     * @Entity
     * @Table(name="roles")
     */
    class Role{
        /**
         * @Id
         * @Column(type="integer") @GeneratedValue
         */
        private $id;

        /**
         * @Column(type="string", length=512)
         */
        private $description;

        /**
         * @Column(type="string", length=64)
         */
        private $name;

        /**
         * GETTERS
         */

        public function getId(){
            return $this->id;
        }

        public function getDescription(){
            return $this->description;
        }

        public function getName(){
            return $this->name;
        }

        /**
         * SETTERS
         */

        public function setDescription( $description ){
            $this->description = $description;
        }

        public function setName( $name ){
            $this->name = $name;
        }
    }
