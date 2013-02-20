<?php
    namespace business;

    class QuickLogin{
        public function createHash( $seed = null ){
            if( $seed == null ){
                $seed = openssl_random_pseudo_bytes(99);
            }

            return hash('whirlpool', $seed . time() . (time() / 64));
        }
    }
