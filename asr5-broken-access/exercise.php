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
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fave_flavor'] = $user['fave_flavor'];
            return true;
        }

        return false;
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * Update a user's favorite flavor in the database.
 *
 * @param string $user_id
 * @param string $fave_flavor
 */
function update_favorites(string $user_id, string $fave_flavor)
{
    $handle = new \PDO('sqlite:users.db');

    $statement = $handle->prepare('UPDATE users SET fave_flavor = :flavor where id = :id');
    $statement->execute([':flavor' => $fave_flavor, ':id' => $user_id]);
}