<?php

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(500);
    die("HTTP POST Required");
}

echo $_SERVER['REQUEST_METHOD'];

/* Jason TODO:

//Load CurrentState and last burn from tables
Evavluate rules and update the CurrentState table.

*/
?>