<?php
$logFile = __DIR__ . '/ip_log.txt';

function getIp() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP']; // Cloudflare
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    }
    return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept'])) {
    $ip = getIp();
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
    $time = date('Y-m-d H:i:s');

    $line = "$time | IP: $ip | UA: $ua\n";
    file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);

    echo "Dostęp przyznany.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Ostrzeżenie</title>
</head>
<body>
    <h2>Uwaga</h2>
    <p>
        Wejście na tę stronę powoduje zapis adresu IP
        w celach bezpieczeństwa i analizy incydentu.
    </p>

    <form method="post">
        <button type="submit" name="accept">Akceptuję</button>
    </form>
</body>
</html>
