<?php
require __DIR__ . '/includes/config.php';

$errors  = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') {
        $errors[] = 'Title is required.';
    }

    if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = 'Please choose an image file.';
    }

    if (!$errors) {
        $file = $_FILES['image'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Upload failed with error code ' . $file['error'] . '.';
        } elseif ($file['size'] > 5000000) { 
            $errors[] = 'File is too large. Max allowed is 5MB.';
        } else {
            
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime  = $finfo->file($file['tmp_name']);

            $allowed = [
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png'  => 'image/png',
                'gif'  => 'image/gif',
                'webp' => 'image/webp',
            ];

            $ext = array_search($mime, $allowed, true);
            if ($ext === false) {
                $errors[] = 'Invalid file type. Allowed: JPG, PNG, GIF, WEBP.';
            } else {
                
                $newFilename = bin2hex(random_bytes(8)) . '.' . $ext;

                $targetDir  = __DIR__ . '/assets/images';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                $targetPath = $targetDir . '/' . $newFilename;

                if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $errors[] = 'Failed to save uploaded file.';
                } else {
                    
                    $stmt = $pdo->prepare(
                        "INSERT INTO images (title, description, filename) 
                         VALUES (:title, :description, :filename)"
                    );
                    $stmt->execute([
                        ':title'       => $title,
                        ':description' => $description,
                        ':filename'    => $newFilename,
                    ]);

                    $success = 'Image uploaded successfully!';
                    
                    $_POST = [];
                }
            }
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<h1 class="mb-3">Upload Image</h1>

<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php elseif ($success): ?>
  <div class="alert alert-success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data" class="card p-3 shadow-sm">
  <div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control" required
           value="<?= htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Description (optional)</label>
    <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Image File (Max 5MB)</label>
    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp" required>
  </div>

  <button type="submit" class="btn btn-primary">Upload</button>
  <a href="index.php" class="btn btn-secondary">Back</a>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
