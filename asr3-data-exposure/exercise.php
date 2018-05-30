<?php

namespace EAMann\BobbyTables\Lesson;

function encrypt($str)
{
    for ($i = 0; $i < 5; $i++) {
        $str = strrev(base64_encode($str));
    }
    return $str;
}

function decrypt($str)
{
    for ($i = 0; $i < 5; $i++) {
        $str = base64_decode(strrev($str));
    }
    return $str;
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
    $decryption_key = getenv('SECRET_KEY');

    $encrypted = file_get_contents('secret.txt');

    return decrypt(hex2bin($encrypted));
}