<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/7/19
 * Time: 16:27
 */

namespace OneSite\Napas\Billing;


/**
 * Class RSA
 * @package OneSite\Napas\Billing
 */
class RSA
{

    /**
     * @param $privateKeyPath
     * @param $publicKeyPath
     * @param array $options
     * @return bool|string
     */
    public function createKeys($privateKeyPath, $publicKeyPath, $options = [])
    {
        $rsa = new \phpseclib\Crypt\RSA();

        $rsa->setEncryptionMode(\phpseclib\Crypt\RSA::ENCRYPTION_PKCS1);

        $password = '';
        if (!empty($options['has_password'])) {
            $password = $this->getPassword();

            $rsa->setPassword($password);
        }

        $bitsKey = !empty($options['bits']) ? $options['bits'] : 2048;

        $keys = $rsa->createKey($bitsKey);

        file_put_contents($privateKeyPath, $keys['privatekey']);
        file_put_contents($publicKeyPath, $keys['publickey']);

        chmod($privateKeyPath, 0600);

        return $password;
    }

    /**
     * @param $publicKeyPath
     * @param $plaintext
     * @return string
     */
    public function encrypt($publicKeyPath, $plaintext)
    {
        $rsaPublic = new \phpseclib\Crypt\RSA();

        $publicKeyData = file_get_contents($publicKeyPath);

        $rsaPublic->setEncryptionMode(\phpseclib\Crypt\RSA::ENCRYPTION_PKCS1);

        $rsaPublic->loadKey($publicKeyData);

        //set hash (I chose sha512 because sha1 apparently has collisions)
        $rsaPublic->setHash('sha512');
        //set MGF hash
        $rsaPublic->setMGFHash('sha512');

        $cipherTextRaw = $rsaPublic->encrypt($plaintext);

        $cipherText = base64_encode($cipherTextRaw);

        return $cipherText;
    }

    /**
     * @param $privateKeyPath
     * @param $password
     * @param $cipherText
     * @return string
     */
    public function decrypt($privateKeyPath, $password, $cipherText)
    {
        $rsaPrivate = new \phpseclib\Crypt\RSA();

        $rsaPrivate->setEncryptionMode(\phpseclib\Crypt\RSA::ENCRYPTION_PKCS1);

        $rsaPrivate->setPassword($password);

        $privateKeyData = file_get_contents($privateKeyPath);

        $rsaPrivate->loadKey($privateKeyData, \phpseclib\Crypt\RSA::PRIVATE_FORMAT_PKCS1);

        //set hash (I chose sha512 because sha1 apparently has collisions)
        $rsaPrivate->setHash('sha512');
        //set MGF hash
        $rsaPrivate->setMGFHash('sha512');

        $decrypted = $rsaPrivate->decrypt(base64_decode($cipherText));

        return $decrypted;
    }

    /**
     * @param $privateKeyPath
     * @param $message
     * @param string $password
     * @return string
     */
    public function sign($privateKeyPath, $message, $password = '')
    {
        $rsaSigner = new \phpseclib\Crypt\RSA();

        $privateKeyData = file_get_contents($privateKeyPath);

        if (!empty($password)) {
            $rsaSigner->setPassword($password);
        }

        $rsaSigner->loadKey($privateKeyData);
        $rsaSigner->setHash('sha512');
        $rsaSigner->setMGFHash('sha512');

        $signature = $rsaSigner->sign($message);

        return base64_encode($signature);
    }

    /**
     * @param $publicKeyPath
     * @param $message
     * @param $signature
     * @return bool
     */
    public function verify($publicKeyPath, $message, $signature)
    {
        $rsaVerifier = new \phpseclib\Crypt\RSA();

        $rsaVerifier->setHash('sha512');
        $rsaVerifier->setMGFHash('sha512');

        $publicKeyData = file_get_contents($publicKeyPath);

        $rsaVerifier->loadKey($publicKeyData);

        $signature = base64_decode($signature);

        return $rsaVerifier->verify($message, $signature);
    }

    /**
     * @return bool|string
     */
    private function getPassword()
    {
        return substr(base64_encode(openssl_random_pseudo_bytes(45)), 0, 32);
    }
}
