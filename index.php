<?php
// načteme data ze souboru JSON
$profileFile = __DIR__ . '/profile.json';
$data = [];
if (file_exists($profileFile)) {
    $json = file_get_contents($profileFile);
    $data = json_decode($json, true);
}

// zajistíme strukturu
if (!isset($data['interests']) || !is_array($data['interests'])) {
    $data['interests'] = [];
}

$message = '';
$messageType = '';

// zpracování formuláře
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new_interest'])) {
        $newInterest = trim($_POST['new_interest']);
        if ($newInterest === '') {
            $message = 'Zájem nesmí být prázdný.';
            $messageType = 'error';
        } else {
            // kontrola duplicity (bez ohledu na velikost písmen)
            $lowered = strtolower($newInterest);
            $exists = false;
            foreach ($data['interests'] as $interest) {
                if (strtolower($interest) === $lowered) {
                    $exists = true;
                    break;
                }
            }
            if ($exists) {
                $message = 'Tento zájem již v seznamu je.';
                $messageType = 'error';
            } else {
                // přidat nový zájem
                $data['interests'][] = $newInterest;
                // uložit zpět do souboru
                $encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                file_put_contents($profileFile, $encoded);

                $message = 'Zájem byl úspěšně přidán.';
                $messageType = 'success';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>IT Profil 4.0</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Moje zájmy</h1>

    <ul>
        <?php foreach ($data['interests'] as $interest): ?>
            <li><?php echo htmlspecialchars($interest, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
    </ul>

    <?php if ($message !== ''): ?>
        <p class="<?php echo htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8'); ?>">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="new_interest" required>
        <button type="submit">Přidat zájem</button>
    </form>
</body>
</html>
