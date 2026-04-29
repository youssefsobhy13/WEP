<?php
// admin_download.php
$dataFile = "results.json";
$results = [];
if (file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    $results = json_decode($content, true) ?? [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Saved Quiz Results</title>
    <style>
        body { font-family: Arial, sans-serif; background: #0b1a2e; color: #eee; padding: 2rem; }
        .container { max-width: 1200px; margin: auto; background: #1e2a3a; padding: 1.5rem; border-radius: 1rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 0.75rem; text-align: left; border-bottom: 1px solid #3b5a7c; }
        th { background: #ffaa44; color: #1a2a3a; }
        .btn { background: #ffaa44; padding: 0.5rem 1rem; border-radius: 2rem; text-decoration: none; color: #1a2a3a; display: inline-block; margin-top: 1rem; }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fas fa-database"></i> All Saved Student Results</h1>
    <a href="?download=csv" class="btn">📥 Download as CSV</a>
    <a href="index.html" class="btn">🏠 Back to Main Site</a>
    
    <?php if (isset($_GET['download']) && $_GET['download'] == 'csv'): ?>
    <?php
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="quiz_results.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Name', 'ID', 'Score', 'Total', 'Percentage', 'Timestamp']);
        foreach ($results as $row) {
            fputcsv($out, [$row['name'], $row['id'], $row['score'], $row['total'], $row['percentage'], $row['timestamp']]);
        }
        fclose($out);
        exit;
    ?>
    <?php endif; ?>
    
    <?php if (empty($results)): ?>
        <p>No results saved yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>Name</th><th>ID</th><th>Score</th><th>Total</th><th>%</th><th>Timestamp</th></tr>
            </thead>
            <tbody>
                <?php foreach(array_reverse($results) as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                    <td><?= htmlspecialchars($r['id']) ?></td>
                    <td><?= $r['score'] ?></td>
                    <td><?= $r['total'] ?></td>
                    <td><?= $r['percentage'] ?>%</td>
                    <td><?= htmlspecialchars($r['timestamp']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>Total records: <?= count($results) ?></p>
    <?php endif; ?>
</div>
</body>
</html>