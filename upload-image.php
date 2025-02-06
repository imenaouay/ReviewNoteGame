<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);

if ($_FILES["file"]["size"] > 5000000) { // Limit file size to 5MB
    echo json_encode(['success' => false, 'message' => 'File size exceeds the limit of 5MB!']);
    exit;
}

$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, and PNG files are allowed!']);
    exit;
}

if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
    echo json_encode(['success' => true, 'url' => $target_file]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error uploading file!']);
}
?>