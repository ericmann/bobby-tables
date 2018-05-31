<?php

namespace EAMann\BobbyTables\Lesson;

function encrypt($str, $key)
{
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

    $cipherText = sodium_crypto_secretbox($str, $nonce, $key);

    return bin2hex($nonce . $cipherText);
}

function decrypt($str, $key)
{
    $concatenated = hex2bin($str);

    $nonce = substr($concatenated, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $cipherText = substr($concatenated, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

    return sodium_crypto_secretbox_open($cipherText, $nonce, $key);
}

/**
 * Get the contents of a secret file on disk holding information about our prize giveaway.
 *
 * The data is "encrypted" to prevent other users from spying on us!
 *
 * @return string
 */
function get_secret(): string
{
    // 621bc7687093d62f1077d17f03b1ef2f800e9a8fec34dd16259c85202af1e42e
    $decryption_key = hex2bin(getenv('SECRET_KEY'));

    $encrypted = file_get_contents('secret.txt');

    return decrypt($encrypted, $decryption_key);
}