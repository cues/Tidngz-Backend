<?php
require "headers.php";
require "response.php";
require "db_pdo.php";
require "data.php";


// Fix: Instantiate the Db class. You cannot use $this in the global scope.
$place_id = $_GET['place_id'] ?? null;

if ($place_id) {
    $db = new Db();
    $db->query("SELECT * from Places WHERE ID = ?");
    $db->bind(1, $place_id);

    $row_place = $db->single();
    $count = $db->count();

    echo $count;
    $db->closeConnection();
    return;
}

// switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
//     case '/':
//         require 'homepage.php';
//         break;
//     case '/contact.php':
//         require 'contact.php';
//         break;
//     case '/API/Articles':
//         require __DIR__ . '/API/Articles/index.php';
//         break;
//     default:
//         http_response_code(404);
//         exit('Not Found');
// }

