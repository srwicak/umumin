<?php
// Lokasi file CSV
$filePath = 'data.csv';

// Membaca file CSV
$data = [];
if (($handle = fopen($filePath, "r")) !== false) {
    $isHeader = true;
    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
        if ($isHeader) { // Lewati header
            $isHeader = false;
            continue;
        }
        $data[] = [
            'Nama Tim' => $row[0],
            'Finalis' => (bool)$row[1],
        ];
    }
    fclose($handle);
}

// Pencarian tim
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$result = null;

if ($keyword) {
    foreach ($data as $row) {
        if (strcasecmp($row['Nama Tim'], $keyword) === 0) { // Case-insensitive comparison
            $result = $row;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Finalis</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; text-align: left; }
        th, td { padding: 8px; }
        .finalist { background-color: #d4edda; color: #155724; }
        .not-finalist { background-color: #f8d7da; color: #721c24; }
        .result { margin-top: 20px; margin-bottom: 100px; padding: 20px; border-radius: 8px; text-align: center; }
    </style>
</head>
<body>
    <h1>Pencarian Finalis</h1>
    <form method="GET">
        <input type="text" name="keyword" placeholder="Masukkan nama tim" value="<?= htmlspecialchars($keyword) ?>">
        <button type="submit">Cari</button>
    </form>

    <?php if ($keyword): ?>
        <div class="result <?= $result ? ($result['Finalis'] ? 'finalist' : 'not-finalist') : 'not-finalist' ?>">
            <?php if ($result): ?>
                <h2>Tim <?= htmlspecialchars($result['Nama Tim']) ?> <?= $result['Finalis'] ? 'Lolos!' : 'Tidak Lolos' ?></h2>
            <?php else: ?>
                <h2>Tim tidak ditemukan.</h2>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <hr>
    <h2>Contoh data CSV: Daftar Tim dan Status</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Tim</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr class="<?= $row['Finalis'] ? 'finalist' : 'not-finalist' ?>">
                    <td><?= htmlspecialchars($row['Nama Tim']) ?></td>
                    <td><?= $row['Finalis'] ? 'Lolos' : 'Tidak Lolos' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
