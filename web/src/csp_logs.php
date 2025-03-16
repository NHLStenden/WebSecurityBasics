<?php
$logFile = "/var/log/apache2/error.log"; // Pas aan voor Alpine: "/var/log/httpd/error.log"

if (!file_exists($logFile)) {
  die("Geen logs gevonden!");
}

// Lees de logs en filter regels met '[CSP REPORT]'
$logLines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$cspLogs = [];

foreach ($logLines as $line) {
  if (strpos($line, '[php:notice]') !== false && strpos($line, '"csp-report":') !== false) {
    // Zoek de JSON in de logregel
    preg_match('/\{.*\}/', $line, $matches);
    if (!empty($matches[0])) {
      $jsonData = json_decode($matches[0], true);
      if ($jsonData && isset($jsonData['csp-report'])) {
        $cspLogs[] = [
          'timestamp' => substr($line, 1, 20), // Haal tijdstip uit de logregel
          'document' => $jsonData['csp-report']['document-uri'] ?? 'N/A',
          'referrer' => $jsonData['csp-report']['referrer'] ?? 'N/A',
          'violated_directive' => $jsonData['csp-report']['violated-directive'] ?? 'N/A',
          'effective_directive' => $jsonData['csp-report']['effective-directive'] ?? 'N/A',
          'blocked_uri' => $jsonData['csp-report']['blocked-uri'] ?? 'N/A',
          'line_number' => $jsonData['csp-report']['line-number'] ?? 'N/A',
          'source_file' => $jsonData['csp-report']['source-file'] ?? 'N/A',
        ];
      }
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
    <link rel="stylesheet" href="css/csp_logs.css">
</head>
<body>

<h2>CSP Reports</h2>

<?php if (empty($cspLogs)): ?>
    <p>Geen CSP overtredingen gevonden.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Tijdstip</th>
            <th>Document</th>
            <th>Referrer</th>
            <th>Overtreden Directive</th>
            <th>Effectieve Directive</th>
            <th>Geblokkeerde URI</th>
            <th>Regelnummer</th>
            <th>Bronbestand</th>
        </tr>
      <?php foreach ($cspLogs as $log): ?>
          <tr>
              <td><?= htmlspecialchars($log['timestamp']) ?></td>
              <td><?= htmlspecialchars($log['document']) ?></td>
              <td><?= htmlspecialchars($log['referrer']) ?></td>
              <td><?= htmlspecialchars($log['violated_directive']) ?></td>
              <td><?= htmlspecialchars($log['effective_directive']) ?></td>
              <td><?= htmlspecialchars($log['blocked_uri']) ?></td>
              <td><?= htmlspecialchars($log['line_number']) ?></td>
              <td><?= htmlspecialchars($log['source_file']) ?></td>
          </tr>
      <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>
