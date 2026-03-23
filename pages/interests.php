<?php
$profileFile = __DIR__ . '/../profile.json';
$data = json_decode(file_get_contents($profileFile), true);

$data['interests'] = $data['interests'] ?? [];

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['new_interest'])) {
        $newInterest = trim($_POST['new_interest']);

        if ($newInterest === '') {
            $message = 'Zájem nesmí být prázdný.';
            $messageType = 'error';
        } else {
            $lowered = strtolower($newInterest);
            $exists = false;

            foreach ($data['interests'] as $interest) {
                if (strtolower($interest) === $lowered) {
                    $exists = true;
                    break;
                }
            }

            if ($exists) {
                $message = 'Tento zájem již existuje.';
                $messageType = 'error';
            } else {
                $data['interests'][] = $newInterest;
                file_put_contents($profileFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                $message = 'Zájem přidán.';
                $messageType = 'success';
            }
        }
    }

    if (isset($_POST['delete_index'])) {
        $idx = intval($_POST['delete_index']);

        if (isset($data['interests'][$idx])) {
            array_splice($data['interests'], $idx, 1);

            file_put_contents($profileFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $message = 'Zájem odstraněn.';
            $messageType = 'success';
        }
    }
}
?>

<h2>Zájmy</h2>

<ul>
<?php foreach ($data['interests'] as $idx => $interest): ?>
    <li>
        <?= htmlspecialchars($interest) ?>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="delete_index" value="<?= $idx ?>">
            <button type="submit">×</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>

<?php if ($message): ?>
<p class="<?= $messageType ?>"><?= $message ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="new_interest" required>
    <button type="submit">Přidat</button>
</form>