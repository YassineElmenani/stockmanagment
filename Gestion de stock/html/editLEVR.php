<?php

include_once("connection.php");

if (isset($_GET['editlevreur'])) {
    $editId = $_GET['editlevreur'];
    $sql = "SELECT * FROM delivery_person WHERE ID_DELIVERY_PERSON = $editId";
    $result = mysqli_query($conn, $sql);
    $rowdata = mysqli_fetch_assoc($result);
   
}

// if (isset($_POST['UpdateDeliveryPerson'])) {
//     $editId = $_GET['editlevreur']; // You need to get this ID from the form

//     $firstName = $_POST['first'];
//     $lastName=  $_POST['last'] ;
//     $adr=$_POST["addres"];
//     $phoneNo=$_POST["nimiro"] ;
//     // Collect other form fields similarly

//     $stmt = $conn->prepare('UPDATE delivery_person SET DELIVERYPERSON_First_Name = ?, DELIVERYPERSON_Last_Name=? ,DELIVERYPERSON_Adresse=?,TELEPHONE_NUMBER=? WHERE ID_DELIVERY_PERSON = $editId');
//     $stmt->bind_param('ssss', $firstName,$lastName,$adr, $phoneNo);

//     if ($stmt->execute()) {
//         // Success, redirect or display a success message
//         header("Location: DELIVERYPERSON.php?updateMessage=success");
//         exit();
//     } else {
//         // Error, redirect or display an error message
//         header("Location: DELIVERYPERSON.php?updateMessage=error");
//         exit();
//     }
// }



// Check if $row is populated before accessing its elements

?>
