<?php
/**
 * These utility functions help for rendering our UI to make password management
 * a bit easier. You're not expected to implement anything here.
 */

namespace EAMann\BobbyTables\Util;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

function show_search(ServerRequestInterface $request, ResponseInterface $response, $results): ResponseInterface
{
    if ($request->getMethod() === 'POST') {
        $params = $request->getParsedBody();
        $body = get_entry_form($params['name'], $results);
    } else {
        $body = get_entry_form('', '');
    }

    $response->getBody()->write($body);

    return $response;
}

function get_entry_form(string $name, $results): string
{
    $body = <<<BODY
<html>
<head>
    <title>Cross-site Scripting</title>
    <style type="text/css">
        #search-form, #results { width: 250px; margin: auto; }
        form > div { margin-top: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    NAME
    <div id="search-form">
        <form method="post" action="">
            <div>
                <label for="name">Name: </label>
                <input type="text" name="name" value="" />
            </div>
            <div>
                <input type="submit" value="Find Speakers!" />
            </div>       
        </form>
    </div>
    <div id="results">
        RESULTS
    </div>
</body>
</html>
BODY;

    $body = str_replace('RESULTS', $results, $body);

    if (empty($name)) {
        $body = str_replace('NAME', '', $body);
    } else {
        $body = str_replace('NAME', 'You searched for: <b>' . $name . '</b>', $body);
    }

    return $body;
}