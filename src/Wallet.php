<?php

namespace SudoCoin;

use phpseclib\Crypt\RSA;
use function Stringy\create as s;
\define('CRYPT_RSA_ENCRYPTION_PKCS1', true);

class Wallet {
    public $address;
    private $private_key;

    public function __construct()
    {
        $rsa = new RSA();
        $rsa->setPassword(random_int(0, 1000000));

        $pair = $rsa->createKey(4096);
        $this->address = $pair['publickey'];
        $this->private_key = $pair['privatekey'];
    }

    public function sign($message): string
    {
        $rsa = new RSA();
        $rsa->loadKey($this->address);
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        return $rsa->encrypt($message);
    }

    public static function ssign($address, $message): string
    {
        $wallet = new self();
        $wallet->address = $address;
        return $wallet->sign($message);
    }

    public static function verify_signature($wallet_address, $message, $signature): bool
    {
        $rsa = new RSA();
        $rsa->loadKey($wallet_address);
        $rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
        return($rsa->encrypt($message) === $signature);
    }
}
