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
    $handle = new \SQLite3('users.db');

    $results = $handle->query('SELECT * from users where name=\'' . $name . '\'');

    $out = '<ul>';
    while($row = $results->fetchArray()) {
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

    passthru("cat files/" . $filename);
    exit();

}