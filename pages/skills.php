<?php
$profileFile = __DIR__ . '/../profile.json';
$data = json_decode(file_get_contents($profileFile), true);

$data['skills'] = $data['skills'] ?? ['general' => [], 'technologies' => []];
?>

<h2>Dovednosti</h2>

<?php if (!empty($data['skills']['general'])): ?>
<ul>
    <?php foreach ($data['skills']['general'] as $skill): ?>
        <li><?= htmlspecialchars($skill) ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if (!empty($data['skills']['technologies'])): ?>
<ul>
    <?php foreach ($data['skills']['technologies'] as $tech): ?>
        <li><?= htmlspecialchars($tech) ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>