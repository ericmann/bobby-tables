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

    $statement = $handle->prepare('SELECT * from users where name = :name');
    $statement->execute([':name' => $name]);

    $out = '<ul>';
    while($row = $statement->fetch()) {
        $out .= '<li>' . $row['name'] . ' - ' . $row['talk'];
    }
    $out .= '</ul>';

    return $out;
}

/**
 * Serve a specific file back to the requester from the /files directory.
 *
 * @param string $filename
 */
function serve_file(string $filename)
{
    header("Content-Type: application/octet-stream");
    header(
        "Content-Disposition: attachment; filename=\"{$filename}\""
    );

    $sanitized = basename($filename);

    passthru("cat files/" . $sanitized);
    exit();

}