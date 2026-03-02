<?php
// načteme data ze souboru JSON
$profileFile = __DIR__ . '/profile.json';
$data = [];
if (file_exists($profileFile)) {
    $json = file_get_contents($profileFile);
    $data = json_decode($json, true);
}

// zajistíme strukturu a další pole
if (!isset($data['interests']) || !is_array($data['interests'])) {
    $data['interests'] = [];
}
if (!isset($data['about'])) {
    $data['about'] = '';
}
if (!isset($data['skills'])) {
    $data['skills'] = ['general' => [], 'technologies' => []];
}
if (!isset($data['projects'])) {
    $data['projects'] = '';
}
if (!isset($data['goal'])) {
    $data['goal'] = '';
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
    <?php if ($data['about'] !== ''): ?>
    <section id="about">
        <h2>O mně</h2>
        <p><?php echo nl2br(htmlspecialchars($data['about'], ENT_QUOTES, 'UTF-8')); ?></p>
    </section>
<?php endif; ?>

<?php if (!empty($data['skills']['general']) || !empty($data['skills']['technologies'])): ?>
    <section id="skills">
        <h2>Co umím</h2>
        <?php if (!empty($data['skills']['general'])): ?>
            <ul>
                <?php foreach ($data['skills']['general'] as $skill): ?>
                    <li><?php echo htmlspecialchars($skill, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if (!empty($data['skills']['technologies'])): ?>
            <ul>
                <?php foreach ($data['skills']['technologies'] as $tech): ?>
                    <li><?php echo htmlspecialchars($tech, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php if ($data['projects'] !== ''): ?>
    <section id="projects">
        <h2>Projekty</h2>
        <p><?php echo nl2br(htmlspecialchars($data['projects'], ENT_QUOTES, 'UTF-8')); ?></p>
    </section>
<?php endif; ?>

<?php if ($data['goal'] !== ''): ?>
    <section id="goal">
        <h2>Cíl</h2>
        <p><?php echo nl2br(htmlspecialchars($data['goal'], ENT_QUOTES, 'UTF-8')); ?></p>
    </section>
<?php endif; ?>

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
