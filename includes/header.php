<?php // includes/header.php ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Photo Gallery</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f7f7f7; }
    .card img { object-fit: cover; height: 220px; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">Photo Gallery</a>
    <div class="ms-auto">
      <a href="upload.php" class="btn btn-outline-light">Upload</a>
    </div>
  </div>
</nav>
<div class="container">
