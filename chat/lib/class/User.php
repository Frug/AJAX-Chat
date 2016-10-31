<?php

class User
{
    private $id;
    private $idTemp;
    private $socialNetwork;
    private $roleCode;
    private $lastStream;
    private $lastLogin;
    private $accessToken;
    private $key = 'bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3';

    public function getId()
    {
        return $this->id;
    }

    public function getIdTemp()
    {
        return $this->idTemp;
    }

    public function getSocialNetwork()
    {
        return $this->socialNetwork;
    }

    public function getRole()
    {
        return $this->roleCode;
    }

    public function getLastStream()
    {
        return $this->lastStream;
    }

    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function generateAccessToken($ip, $userAgent, $userId)
    {
        return md5($ip.$userAgent.$userId);
    }

    public function auth($authData, $isPortal = false)
    {
        if ($authData) {
            $userData = json_decode(trim($this->decrypt($authData)), true);
            if ($this->isRoleAccessAllowed($userData['role_code'], $isPortal) && $userData['access_token'] === $this->generateAccessToken($this->getRemoteIPAddress(), $_SERVER['HTTP_USER_AGENT'], $userData['user_id'])) {
                $this->setProperties($userData);
                return true;
            }
        }
        // Not Authorized
        self::forbidden();
    }

    protected function isRoleAccessAllowed($userRole, $isPortal = false)
    {
        if($isPortal && $userRole == 'USER') {
            self::forbidden("Users have no access to this area");
        }
        return true;
    }

    public function authorize($user, $socialNetwork, $code, $app)
    {
        // data for encryption
        $userData = [
            'user_id' => $user['id'],
            'user_id_temp' => $user['id'],
            'role_code' => $user['role_code'],
            'last_login' => $user['last_login'],
            'social_network' => $socialNetwork,
            'access_token' =>  $this->generateAccessToken($this->getRemoteIPAddress(), $_SERVER['HTTP_USER_AGENT'], $user['id']),
        ];
        $this->setProperties($userData);
        // set encrypted cookie
        // expires never
        // http only
        $app->setCookie('sp', $this->encrypt(json_encode($userData)), '2 days', null, null, null, true);
        // TODO: this should be removed in the future
        $app->setCookie('code', $code, '2 days');
        $app->setCookie('user_id_temp', $user['id'], '2 days');
        $app->setCookie('role_code', $user['role_code'], '2 days');
        $app->setCookie('last_login', $user['last_login'], '2 days');
        $app->setCookie('initialLogin', 'messageNotSeen', '2 days');
        $app->deleteCookie('targetURL');
        $app->deleteCookie('stateCode');
    }

    private function setProperties($data)
    {
        if (isset($data['user_id'], $data['role_code'], $data['last_login'], $data['access_token'])) {
            $this->id = $data['user_id'];
            $this->idTemp = $data['user_id'];
            $this->roleCode = $data['role_code'];
            $this->socialNetwork = $data['social_network'];
            $this->accessToken = $data['access_token'];
            $this->lastLogin = $data['last_login'];
        }
    }

    public function encrypt($text)
    {
        $key = pack('H*', $this->key);
        // create a random IV to use with CBC encoding
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        // creates a cipher text compatible with AES (Rijndael block size = 128)
        // to keep the text confidential
        // only suitable for encoded input that never ends with value 00h
        // (because of default zero padding)
        $ciphertext = mcrypt_encrypt(
            MCRYPT_RIJNDAEL_128,
            $key,
            $text,
            MCRYPT_MODE_CBC,
            $iv
        );
        // prepend the IV for it to be available for decryption
        $ciphertext = $iv . $ciphertext;
        // encode the resulting cipher text so it can be represented by a string
        return base64_encode($ciphertext);
    }

    public function decrypt($text)
    {
        $key = pack('H*', $this->key);
        // create a random IV to use with CBC encoding
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $ciphertext_dec = base64_decode($text);
        // retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        // retrieves the cipher text (everything except the $iv_size in the front)
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);
        // may remove 00h valued characters from end of plain text
        return mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $key,
            $ciphertext_dec,
            MCRYPT_MODE_CBC,
            $iv_dec
        );
    }

    public static function forbidden($message = "Your session has expired. please login again")
    {
        http_response_code(403);
        header('Content-Type: application/json');
        exit(json_encode([
            'messageType' => 'error',
            'message' => $message
        ]));
    }

    public function getRemoteIPAddress() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];

        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
}