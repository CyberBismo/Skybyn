<?php include_once "../assets/functions.php";

header('Content-Type: application/json; charset=utf-8');

$data = array(
    'color' => skybyn("color_one")
);

echo json_encode($data);

?>