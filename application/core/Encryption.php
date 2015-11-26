<?php

declare(strict_types = 1);

class Encryption
{
    const CIPHER = 'aes-256-cbc';
    const HASH_FUNCTION = 'sha256';

    /**
     * Encryption constructor.
     */
    private function __construct(){}

    /**
     * Encrypt a string.
     * @param $plain
     * @return string
     * @throws Exception
     */
    public static function encrypt($plain){
        if(!function_exists('openssl_cipher_iv_length') ||
            !function_exists('openssl_random_pseudo_bytes') ||
            !function_exists('openssl_encrypt')){
            throw new Exception("Encryption function don't exists");
        }

        // generate initialization vector, different every time
        $iv_size = openssl_cipher_iv_length(self::CIPHER);
        $iv = openssl_random_pseudo_bytes($iv_size);

        // generate key for authentication using ENCRYPTION_KEY & HMAC_SALT
        $key = mb_substr(hash(self::HASH_FUNCTION, Config::get('ENCRYPTION_KEY') . Config::get('HMAC_SALT')), 0, 32, '8bit');

        // append initialization vector
        $encrypted_string = openssl_encrypt($plain, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
        $ciphertext = $iv . $encrypted_string;

        // apply the HMAC
        $hmac = hash_hmac('sha256', $ciphertext, $key);
        return $hmac . $ciphertext;
    }
    /**
     * Decrypt a string.
     *
     * @access public
     * @static static method
     * @param  string $ciphertext
     * @return string
     * @throws Exception If $ciphertext is empty, or If functions don't exists
     */
    public static function decrypt($ciphertext){
        if(empty($ciphertext)){
            throw new Exception("the string to decrypt can't be empty");
        }
        if(!function_exists('openssl_cipher_iv_length') ||
            !function_exists('openssl_decrypt')){
            throw new Exception("Encryption function don't exists");
        }
        // generate key used for authentication using ENCRYPTION_KEY & HMAC_SALT
        $key = mb_substr(hash(self::HASH_FUNCTION, Config::get('ENCRYPTION_KEY') . Config::get('HMAC_SALT')), 0, 32, '8bit');
        // split cipher into: hmac, cipher & iv
        $macSize = 64;
        $hmac = mb_substr($ciphertext, 0, $macSize, '8bit');
        $iv_cipher = mb_substr($ciphertext, $macSize, null, '8bit');
        // generate original hmac & compare it with the one in $ciphertext
        $originalHmac = hash_hmac('sha256', $iv_cipher, $key);

        if (!function_exists("hash_equals")) {
            throw new Exception("Function hash_equals() doesn't exist!");
        }
        if(!hash_equals($hmac, $originalHmac)){
            return false;
        }
        // split out the initialization vector and cipher
        $iv_size = openssl_cipher_iv_length(self::CIPHER);
        $iv = mb_substr($iv_cipher, 0, $iv_size, '8bit');
        $cipher = mb_substr($iv_cipher, $iv_size, null, '8bit');
        return openssl_decrypt($cipher, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
    }
}