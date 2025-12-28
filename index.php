<?php
require "headers.php";
require "response.php";
require "db_pdo.php";
require "data.php";


// $place = $_GET['place_id'];
// echo $place;
// return;

$this->query("SELECT * from Places WHERE ID = ?");
        $this->bind(1, $place_id);

        $row_place = $this->single();
        $count = $this->count();

        echo $count;
        return;



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

