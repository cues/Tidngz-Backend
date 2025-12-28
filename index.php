<?php
require "headers.php";
require "response.php";
require "db_pdo.php";
require "data.php";


switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/homepage.php':
        require 'homepage.php';
        break;
    case '/contact.php':
        require 'contact.php';
        break;
    default:
        http_response_code(404);
        exit('Not Found');
}