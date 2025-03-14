<?php
header("Content-Type: application/json");

// CORS-configuratie (variëren voor testen)
header("Access-Control-Allow-Origin: http://localhost:8080"); // Of probeer een specifiek domein
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

$todoList = [
    ["id" => 1, "task" => "Docker configureren"],
    ["id" => 2, "task" => "CORS testen"],
    ["id" => 3, "task" => "CSP regels instellen"]
];

echo json_encode($todoList);
?>
