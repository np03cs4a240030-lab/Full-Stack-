<?php include 'header.php'; ?>

<?php
$message = "";

function uploadPortfolioFile($file) {
    $allowed = ['image/png', 'image/jpeg', 'application/pdf'];
    $maxSize = 2 * 1024 * 1024;

    if (!in_array($file['type'], $allowed)) {
        throw new Exception("Invalid file type.");
    }

    if ($file['size'] > $maxSize) {
        throw new Exception("File too large (max 2MB).");
    }

    if (!is_dir("uploads")) {
        mkdir("uploads");
    }

    $newName = time() . "_" . basename($file['name']);
    $path = "uploads/" . $newName;

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        throw new Exception("Upload failed.");
    }
}

if (isset($_POST['uploadbtn'])) {
    try {
        if ($_FILES['portfoliofile']['error'] !== 0) {
            throw new Exception("Please select a file.");
        }

        uploadPortfolioFile($_FILES['portfoliofile']);
        $message = "✅ File uploaded successfully!";
    } catch (Exception $e) {
        $message = "❌ " . $e->getMessage();
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="portfoliofile" accept=".png,.jpg,.pdf"><br><br>
    <button type="submit" name="uploadbtn">Upload</button>
</form>

<p><?php echo $message; ?></p>

<?php include 'footer.php'; ?>
