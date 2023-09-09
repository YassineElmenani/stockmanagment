

function toggleSidebar() {
      document.querySelector("#sidebar").classList.toggle("collapsed");
    }
    
    const TogglSidebare= document.querySelector("#sidebar-toggle");
    SidebarToggle.addEventListener("click",function(){
      document.querySelector("#sidebar").classList.toggle("collapsed");
      event.preventDefault();
    });

  //   function updateQuantity(change) {
  //     const quantityInput = document.getElementById('quantity');
  //     let newQuantity = parseInt(quantityInput.value) + change;
  //     if (newQuantity < 0) {
  //         newQuantity = 0;
  //     }
  //     quantityInput.value = newQuantity;
  // }
  document.addEventListener("DOMContentLoaded", function() {
    const updateButtons = document.querySelectorAll(".update-status");

    updateButtons.forEach(button => {
        button.addEventListener("click", function() {
            const orderId = this.getAttribute("data-order-id");
            updateOrderStatus(orderId);
        });
    });

    function updateOrderStatus(orderId) {
        // Make an AJAX request to update_status.php
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText === "success") {
                    // Display a success message or update the UI
                    alert("Order status updated successfully!");
                    // Optionally, refresh the page or update the stock data
                } else {
                    alert("Failed to update order status.");
                }
            }
        };
        xhr.send("order_id=" + orderId);
    }
});







//  $(document).ready(function() {
//     $('#add-delivery-person-form').submit(function(event) {
//         event.preventDefault(); // Prevent default form submission

//         // Collect form data
//         var formData = {
//             frstname: $('#firstName').val(),
//             lstname: $('#lastName').val(),
//             address: $('#address').val(),
//             number: $('#phone').val()
//         };

//         // Send AJAX request
//         $.ajax({
//             type: 'POST',
//             url: 'add_delivery_person.php', // Update with the correct URL
//             data: formData,
//             success: function(response) {
//                 if (response.success) {
//                     alert(response.message);
//                     // Optionally, you can reset the form fields after successful submission
//                     $('#add-delivery-person-form')[0].reset();
//                 } else {
//                     alert(response.message);
//                 }
//             },
//             error: function(error) {
//                 console.error(error);
//                 alert('An error occurred.');
//             }
//         });
//     });
// });
