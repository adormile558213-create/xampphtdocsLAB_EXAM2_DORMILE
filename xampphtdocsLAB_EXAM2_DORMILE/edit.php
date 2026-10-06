<?php
include 'config.php';
$errors = [];
$id = intval($_GET['id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) die("Item not found");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price       = trim($_POST['price']);

    if ($name === '')                         $errors[] = "Name is required";
    if ($description === '')                  $errors[] = "Description is required";
    if ($price === '' || !is_numeric($price)) $errors[] = "Valid price is required";

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE items SET name=?, description=?, price=? WHERE id=?");
        $stmt->bind_param("ssdi", $name, $description, $price, $id);
        $stmt->execute();
        header("Location: index.php?msg=Item updated successfully");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="max-width: 640px;">
    <div class="page-header">
        <h2><i class="fa-solid fa-pen-to-square"></i> Edit Item #<?= $item['id'] ?></h2>
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
                   value="<?= htmlspecialchars($item['name']) ?>">
        </div>

        <div class="form-group">
            <label><i class="fa-solid fa-align-left"></i> Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($item['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label><i class="fa-solid fa-dollar-sign"></i> Price</label>
            <input type="number" step="0.01" name="price" class="form-control"
                   value="<?= htmlspecialchars($item['price']) ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-floppy-disk"></i> Update Item
            </button>
            <a href="index.php" class="btn btn-secondary">
                <i class="fa-solid fa-xmark"></i> Cancel
            </a>
        </div>
    </form>
</div>
</body>
</html>