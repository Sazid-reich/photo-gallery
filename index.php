<?php
require __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/header.php';


$stmt = $pdo->query("SELECT id, title, description, filename, upload_date FROM images ORDER BY upload_date DESC");
$images = $stmt->fetchAll();
?>

<h1 class="mb-3">All Photos</h1>

<?php if (!$images): ?>
  <p class="text-muted">No images uploaded yet.</p>
<?php else: ?>
  <div class="row g-4">
    <?php foreach ($images as $img): 
      $title = htmlspecialchars($img['title'] ?? '', ENT_QUOTES, 'UTF-8');
      $desc  = htmlspecialchars($img['description'] ?? '', ENT_QUOTES, 'UTF-8');
      $file  = htmlspecialchars($img['filename'], ENT_QUOTES, 'UTF-8');
      $date  = date('M d, Y h:i A', strtotime($img['upload_date']));
    ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card h-100 shadow-sm">
          <img src="assets/images/<?= $file ?>" class="card-img-top" alt="<?= $title ?>" loading="lazy">
          <div class="card-body">
            <h5 class="card-title mb-1"><?= $title ?></h5>
            <p class="card-text small text-muted mb-2"><?= $date ?></p>
            <?php if ($desc): ?>
              <p class="card-text"><?= nl2br($desc) ?></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
