<?php

include_once("connection.php");



if (isset($_GET['deletelevreur'])) {
    $lvrId = $_GET['deletelevreur'];

    // Delete the user from the database
    $stmt = $conn->prepare('DELETE FROM delivery_person WHERE ID_DELIVERY_PERSON  = ?');
    $stmt->bind_param('i', $lvrId );
    if ($stmt->execute()) {
        header("Location: DELIVERYPERSON.php?deleteMessage=success");
        exit();
    } else {
        header("Location: DELIVERYPERSON.php?deleteMessage=error");
        exit();
    }
}




?>