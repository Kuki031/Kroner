<?php
function handlePictureUploads($pdo, $resource, $attr_pic, $id) {
    $file = $_FILES['file'];
    $uploadDir = 'uploads/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
            
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if ($file['error'] === UPLOAD_ERR_OK && in_array($file['type'], $allowedTypes)) {
        $filename = time() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {

            $sqlQuery = "UPDATE $resource SET $attr_pic = :targetPath WHERE id = :id;";
            $params = [
                ":targetPath" => $targetPath,
                ":id" => $id
            ];
            $stmt = $pdo->prepare($sqlQuery);
            $stmt->execute($params);
            }
        }
    }
