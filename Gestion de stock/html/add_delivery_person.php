<?php
session_start(); 
include_once("connection.php");




if (isset($_POST['Addlvreur'])) {
  $First = $_POST['frstname'];
  $Last = $_POST['lstname'];
  $Address = $_POST['address'];
  $phone = $_POST['number'];

  $stmt = $conn->prepare('INSERT INTO delivery_person (DELIVERYPERSON_First_Name,DELIVERYPERSON_Last_Name,DELIVERYPERSON_Adresse,TELEPHONE_NUMBER) VALUES (?, ?, ?, ?)');
  $stmt->bind_param('sssi', $First, $Last, $Address, $phone);

  if ($stmt->execute()) {
    header("Location: DELIVERYPERSON.php?addMessage=success");
} else {
    header("Location: DELIVERYPERSON.php?addMessage=error");
}
exit();
}
  
?>