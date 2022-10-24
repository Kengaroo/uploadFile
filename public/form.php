<!DOCTYPE>
<html>
<?php
$uploadDir = 'uploads/';
if ($_SERVER['REQUEST_METHOD'] === "GET") { 
    $file2delete = isset($_GET['fname']) ? $_GET['fname'] : '';
    if ($file2delete) {
        unlink($uploadDir . $file2delete);
        echo "Photo a ete supprimee";
    }
}
if ($_SERVER['REQUEST_METHOD'] === "POST") { 
    $errors = '';
    $success = '';
    $authorizedExtensions = ['jpg','png', 'gif', 'webp'];
    $maxFileSize = 	1048576;    
    $uploadFile = $uploadDir . basename($_FILES['avatar']['name']);
    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    
    if (!empty($_FILES)) {
        if (!$_FILES['avatar']['error']) {
            if (in_array($extension, $authorizedExtensions)) {
                if (file_exists($_FILES['avatar']['tmp_name'])) { 
                    if (filesize($_FILES['avatar']['tmp_name']) <= $maxFileSize) {
                        $file_name_new = uniqid('', true);
                        $file_destination = $uploadDir . $file_name_new . '.' . $extension;
                        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $file_destination)) {
                            $success = "[{$_FILES['avatar']['name']}] etait telecharge";
                        } else {
                            $errors = "[{$_FILES['avatar']['name']}] n'etiat pas telecharge";
                        } 
                    } else {
                        $errors = "Votre fichier doit faire moins de 1M !";
                    }
                } else {
                    $errors = "Fichier " . $_FILES['avatar']['tmp_name'] ." n'existe pas";
                }
            } else {
                $errors = "Veuillez sélectionner une image de type ". implode(', ' ,$authorizedExtensions) . " ! Et pas '" . $extension . "'";
            }
        } else {
            $errors = "Il y a une erreur en telechargeant votre fichier. Code d'erreur " . $_FILES['avatar']['error'];
        }
    } else {
        var_dump($_FILES); echo 'Empty';
    }
    if ($success) {
        echo $success . '<br/> Homer Simpson, 69 ans.<br/><div><a href="/form.php?fname=' . $file_name_new . '.' . $extension . '">Delete</a></div>';
        echo '<img src="' . $file_destination . '"/>';
    } else {
        echo $errors;
    }
}
?>
    <form method="post" enctype="multipart/form-data">
        <label for="imageUpload">Upload an profile image</label>    
        <input type="file" name="avatar" id="imageUpload" />
        <button name="send">Send</button>
    </form>
</html>