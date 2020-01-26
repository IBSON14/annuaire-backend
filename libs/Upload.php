<?php

class Upload {

    public function loadFile() {
        
        $target_file = FILE_DIR . basename($_FILES["fileToUpload"]["name"]);
        $fileMimeTyp = pathinfo($target_file, PATHINFO_EXTENSION);

        // Check if file already exists
        if (file_exists($target_file)) {
            return "1;Désolé. Le fichier existe déjà.";
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            return "1;La taille est trop grande.";
        }
        // Allow certain file formats
        if ($fileMimeTyp != "xlsx") {
            return "1;Le fichier doit être de type excel." . $fileMimeTyp;
        }
        // Check if $uploadOk is set to 0 by an error
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            return "0;".basename($_FILES["fileToUpload"]["name"]);
        } else {
            return "1;Désolé. Erreur lors du chargement.";
        }
    }

}
