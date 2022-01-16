<?php

/**
 * Class LibSodiumWrapper for the PHP LibSodium library.
 *
 * Encrypt/decrypt a given string and Encrypt/Decrypt a given string with base 64 encoding.
 *
 * @author Jakub Chamera
 * @package Coursework
 * Date: 08/01/2022
 */

namespace Coursework;

class LibSodiumWrapper
{
    /**
     * @var -The key utilised to initialise encryption.
     */
    private $key;

    /**
     * LibSodiumWrapper constructor.
     * Initialises encryption using a key.
     */
    public function __construct()
    {
        $this->initialiseEncryption();
    }

    /**
     * Wipes the current buffer on exit.
     * @throws \SodiumException
     */
    public function __destruct()
    {
        sodium_memzero($this->key);
    }

    /**
     * Function for initialising encryption using a key upon the construction of the class.
     */
    private function initialiseEncryption()
    {
        $this->key = 'The boy stood on the burning dek';

        if (mb_strlen($this->key, '8bit') !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
            throw new RangeException('Key is not the correct size (must be 32 bytes).');
        }
    }

    /**
     * Return an array containing individual values for each of the actual encrypted string and the nonce used to
     * perform the encryption. Need to append the two together for the decryption to work.
     * @param $string_to_encrypt -The string to be encrypted.
     * @return array Returns an array of encrypted data and the nonce used to encrypt it.
     * @throws \Exception
     */
    public function encrypt($string_to_encrypt)
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $encryption_data = [];

        $encrypted_string = '';
        $encrypted_string = sodium_crypto_secretbox(
            $string_to_encrypt,
            $nonce,
            $this->key
        );

        $encryption_data['nonce'] = $nonce;
        $encryption_data['encrypted_string'] = $encrypted_string;
        $encryption_data['nonce_and_encrypted_string'] = $nonce . $encrypted_string;

        sodium_memzero($string_to_encrypt);
        return $encryption_data;
    }

    /**
     * Function used for decrypting data to make it readable.
     * @param $base64_wrapper -Instance of the Base64Wrapper class.
     * @param $string_to_decrypt -The string value to be decrypted.
     * @return string Returns a string of decrypted data.
     * @throws \SodiumException
     */
    public function decrypt($base64_wrapper, $string_to_decrypt)
    {
        $decrypted_string = '';
        $decoded = $base64_wrapper->decode_base64($string_to_decrypt);

        if ($decoded === false)
        {
            throw new \Exception('Ooops, the encoding failed');
        }
        
        if (mb_strlen($decoded, '8bit') < (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES))
        {
            throw new \Exception('Oops, the message was truncated');
        }

        $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');

        $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $decrypted_string = sodium_crypto_secretbox_open(
            $ciphertext,
            $nonce,
            $this->key
        );

        if ($decrypted_string === false)
        {
            throw new \Exception('the message was tampered with in transit');
        }

        sodium_memzero($ciphertext);

        return $decrypted_string;
    }
}

