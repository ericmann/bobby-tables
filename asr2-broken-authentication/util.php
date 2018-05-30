<?php
/**
 * These utility functions help for rendering our UI to make password management
 * a bit easier. You're not expected to implement anything here.
 */

namespace EAMann\BobbyTables\Util;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

function show_login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
{
    $query = $request->getQueryParams();
    if (isset($query['error']) && $query['error'] === 'notloggedin') {
        $error = 'Please log in to continue';
    } else {
        $error = '';
    }

    if ($request->getMethod() === 'POST') {
        $body = $request->getParsedBody();
        $username = $body['username'];
        $password = $body['password'];
        $error = 'Incorrect username or password';
    } else {
        $username = '';
        $password = '';
    }

    $body = get_entry_form($username, $password, $error);
    $response->getBody()->write($body);

    return $response;
}

function get_entry_form(string $username, string $password, string $error): string
{
    $body = <<<BODY
<html>
<head>
    <title>Module 1 | Lesson 1 | Password Validation</title>
    <style type="text/css">
        #prompt { text-align: center; }
        #errors { color: darkred; text-align: center; }
        #auth-form { width: 250px; margin: auto; }
        form > div { margin-top: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div id="prompt">
        <p>Please log in below to view the secret dashbord:</p>
    </div>
    ERRORS
    <div id="auth-form">
        <form method="post" action="">
            <div>
                <label for="username">Username: </label>
                <input type="text" name="username" value="USERNAME" />
            </div>
            <div>
                <label for="password">Password: </label>
                <input type="password" name="password" value="PASSWORD" />
            </div>     
            <div>
                <input type="submit" value="Log in!" />
            </div>       
        </form>
    </div>
</body>
</html>
BODY;

    if (empty($error)) {
        $body = str_replace('ERRORS', '', $body);
    } else {
        $errorMarkup = sprintf('<div id="errors"><strong>%s</strong></div>', $error);
        $body = str_replace('ERRORS', $errorMarkup, $body);
    }

    $body = str_replace('USERNAME', $username, $body);
    return str_replace('PASSWORD', $password, $body);
}

function welcome($username): string
{
    $body = <<<BODY
<html>
<head>
    <title>Broken Authentication</title>
    <style type="text/css">
        #prompt { text-align: center; }
        #auth-form { width: 250px; margin: auto; }
        form > div { margin-top: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div id="prompt">
        <p>Welcome, USERNAME!</p>
        OTHER
    </div>
</body>
</html>
BODY;

    if ($username === 'admin') {
        $body = str_replace('OTHER', '<a href="">Manage other accounts</a>', $body);
    } else {
        $body = str_replace('OTHER', '', $body);
    }

    return str_replace('USERNAME', htmlspecialchars($username), $body);
}

/**
 * Find a specific user in the system
 *
 * @param string $username
 *
 * @throws \Exception
 *
 * @return array
 */
function get_user_by_username(string $username): array
{
    $handle = new \PDO('sqlite:users.db');

    $statement = $handle->prepare('SELECT * from users where username = :username');
    $statement->execute([':username' => $username]);

    $users = $statement->fetchAll();

    if (empty($users)) {
        throw new \Exception(sprintf('No user with username %s found!', $username));
    }

    return $users[0];
}