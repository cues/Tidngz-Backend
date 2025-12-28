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
