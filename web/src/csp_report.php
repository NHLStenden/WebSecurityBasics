<?php
// Stel header in om JSON te ontvangen
header("Content-Type: application/json");

// Haal de JSON-data op
$data = file_get_contents("php://input");

// Log de overtreding naar een bestand
$file = 'csp_log.txt';
$logEntry = date("Y-m-d H:i:s") . " - " . $data . PHP_EOL;
error_log($logEntry);

echo json_encode(["status" => "success", "message" => "CSP report logged"]);
?>
