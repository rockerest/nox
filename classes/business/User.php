<?php
    namespace business;

    class User{
        public function hasGravatar( $user ){
            $grav = "https://secure.gravatar.com/avatar/" . md5( strtolower( trim( $user->getAuthentication()->getIdentity() ) ) ) . "?d=404";
            // test for gravatar
            $atar = curl_init( $grav );
            curl_setopt_array( $atar, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_NOBODY => true
            ));

            curl_exec( $atar );
            $code = curl_getinfo( $atar, CURLINFO_HTTP_CODE );
            curl_close( $atar );

            return ($code == '404') ? false : true;
        }

        public function getGravatarUrl( $user, $size = '16', $default = 'identicon', $r = 'x' ){
            if( $this->hasGravatar( $user ) ){
                $request = 's=' . $size . '&d=' . $default . '&r=' . $r;
                $url = "https://secure.gravatar.com/avatar/" . md5( strtolower( trim( $user->getAuthentication()->getIdentity() ) ) ) . "?" . $request;

                return $url;
            }
            else{
                return false;
            }
        }

        public function getIconUrl( $user, $size = '16', $default = 'identicon', $r = 'x' ){
            switch( strtolower( $user->getGender() ) ){
                case 'm':
                    $icon = 'user';
                    break;
                case 'f':
                    $icon = 'user-female';
                    break;
                default:
                    $icon = 'user-silhouette';
                    break;
            }

            if( !($url = $this->getGravatarUrl( $user )) ){
                $url = '/images/icons/16/' . $icon . '.png';
            }

            return $url;
        }
    }
?>
