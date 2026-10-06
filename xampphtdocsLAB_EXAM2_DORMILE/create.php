<?php
include 'config.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price       = trim($_POST['price']);

    if ($name === '')                                     $errors[] = "Name is required";
    if ($description === '')                              $errors[] = "Description is required";
    if ($price === '' || !is_numeric($price))             $errors[] = "Valid price is required";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO items (name, description, price) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $name, $description, $price);
        $stmt->execute();
        header("Location: index.php?msg=Item created successfully");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="max-width: 640px;">
    <div class="page-header">
        <h2><i class="fa-solid fa-square-plus"></i> Add New Item</h2>
    </div>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <div>
                <i class="fa-solid fa-triangle-exclamation"></i>
                <strong>Please fix the following:</strong>
                <?php foreach ($errors as $e): ?>
                    <div style="margin-left: 22px;">• <?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label><i class="fa-solid fa-tag"></i> Item Name</label>
            <input type="text" name="name" class="form-control"
                   placeholder="e.g. Wireless Mouse"
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label><i class="fa-solid fa-align-left"></i> Description</label>
            <textarea name="description" class="form-control"
                      placeholder="Describe this item..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label><i class="fa-solid fa-dollar-sign"></i> Price</label>
            <input type="number" step="0.01" name="price" class="form-control"
                   placeholder="0.00"
                   value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-floppy-disk"></i> Save Item
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="fa-solid fa-xmark"></i> Cancel
            </a>
        </div>
    </form>
</div>
</body>
</html>