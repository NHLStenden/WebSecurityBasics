<?php
$logfile = 'csp_log.txt';

// Lezen van het logbestand
$logs = [];
if (file_exists($logfile)) {
    $logEntries = file($logfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $logEntries = array_reverse($logEntries); // Laatste logs eerst tonen
    foreach ($logEntries as $log) {
        $logData = json_decode(substr($log, 21), true); // Verwijder datum en parse JSON
        if ($logData) {
            $cspViolations[] = [
                "Date" => substr($log, 0, 19),
                "Blocked URI" => $logData["blocked-uri"] ?? "N/A",
                "Violated Directive" => $logData["violated-directive"] ?? "N/A",
                "Original Policy" => $logData["original-policy"] ?? "N/A",
                "Document URI" => $logData["document-uri"],
                "Referrer" => $logData["referrer"] ?? "N/A"
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSP Logs</title>

    <meta http-equiv="Content-Security-Policy"
          content="default-src 'self';script-src 'self';style-src 'self';connect-src 'self'">
    <link rel="stylesheet" href="css/csp_logs.css">
</head>
<body>
<h1>CSP Overtredingen</h1>
<table>
    <tr>
        <th>Datum</th>
        <th>Blocked URI</th>
        <th>Document URI</th>
        <th>Referrer</th>
        <th>Overtreden Regel</th>
    </tr>
    <?php if (!empty($cspViolations)): ?>
        <?php foreach ($cspViolations as $entry): ?>
            <tr>
                <td><?php echo htmlspecialchars($entry["Date"]); ?></td>
                <td><?php echo htmlspecialchars($entry["Blocked URI"]); ?></td>
                <td><?php echo htmlspecialchars($entry["Referrer"]); ?></td>
                <td><?php echo htmlspecialchars(json_encode($entry["Blocked URI"])); ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">Geen CSP-overtredingen gevonden.</td>
        </tr>
    <?php endif; ?>
</table>
</body>
</html>
