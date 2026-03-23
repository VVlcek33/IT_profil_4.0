<?php
$profileFile = __DIR__ . '/../profile.json';
$data = json_decode(file_get_contents($profileFile), true);

// jistota struktury
$data['about'] = $data['about'] ?? '';
$data['skills'] = $data['skills'] ?? ['general' => [], 'technologies' => []];
$data['projects'] = $data['projects'] ?? '';
$data['goal'] = $data['goal'] ?? '';
?>

<?php if ($data['about'] !== ''): ?>
<section>
    <h2>O mně</h2>
    <p><?= nl2br(htmlspecialchars($data['about'])) ?></p>
</section>
<?php endif; ?>

<?php if (!empty($data['projects'])): ?>
<section>
    <h2>Projekty</h2>
    <p><?= nl2br(htmlspecialchars($data['projects'])) ?></p>
</section>
<?php endif; ?>

<?php if (!empty($data['goal'])): ?>
<section>
    <h2>Cíl</h2>
    <p><?= nl2br(htmlspecialchars($data['goal'])) ?></p>
</section>
<?php endif; ?>