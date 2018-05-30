<?php

namespace EAMann\BobbyTables\Lesson;

use function EAMann\BobbyTables\Util\get_user_by_username;

/**
 * The authentication form will send us the user's input for their authentication
 * credentials. Your task is to validate that the username and password match what's
 * expected from them. For a first example, we want to compare our user-provided input
 * to something static (like a hard-coded string).
 *
 * You should also handle the potential of _multiple_ users for the system. How will we
 * match different usernames to different passwords?
 *
 * @param string $username
 * @param string $password
 *
 * @return bool
 */
function validate_auth(string $username, string $password)
{
    try {
        $user = get_user_by_username($username);

        if (password_verify($password, $user['password'])) {
            setcookie('username', $username);
            return true;
        }

        return false;
    } catch (\Exception $e) {
        return false;
    }
}