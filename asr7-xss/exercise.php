<?php

namespace EAMann\BobbyTables\Lesson;

/**
 * Search the user database for a given user based on their name and return a list of any talks they're giving
 * this year at php[tek].
 *
 * @param string $name
 *
 * @return string
 */
function find_user(string $name): string
{
    $handle = new \PDO('sqlite:users.db');
    $statement = $handle->prepare('SELECT * FROM users WHERE name = :name');
    $statement->execute([':name' => $name]);

    $out = '<ul>';
    while($row = $statement->fetch()) {
        $out .= '<li>' . $row['name'] . ' - ' . $row['talk'];
    }
    $out .= '</ul>';

    return $out;
}