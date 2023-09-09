<?php
session_start();
include_once("connection.php");



if (!isset( $_SESSION['firstname'] )&&!isset($_SESSION['email'])&&!isset($_SESSION['lastname'])){
  header('Location: login.php');
  exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>header</title>
</head>

<body>
    <!-- ======== Main wrapper for dashboard =========== -->
    

<!-- ========= Main navbar section of dashboard ======= -->

<!-- <nav class="navbar navbar-expand px-3 border-bottom">
  <button class="btn" onclick="toggleSidebar()" data-bs-target="#sidebar" data-bs-toggle="collapse">
    <span class="navbar-toggler-icon"></span>
  </button>
  
</nav> -->

<nav class="navbar navbar-expand-lg sticky-top top-0">
  <div class="container-fluid">
    
     <button class="btn" onclick="toggleSidebar()" data-bs-target="#sidebar" data-bs-toggle="collapse">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="sidebar-logo">
    <a href="Dashbord.php">STE TAMANAR-GAZ</a>
  </div>
    <div class="search-center">
      <form class="d-flex">
        <div class="input-group">
          <input type="search" class="form-control" placeholder="Search" aria-label="Search..."  aria-describedby="button-addon2">
          <button class="btn btn-outline-secondary" type="button" id="button-addon2"><i class="bi bi-search"></i></button>
        </div>
      </form>
    </div>
    <div class="dropdown-right">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-fill"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <?php if (isset( $_SESSION['firstname'] )&&isset($_SESSION['lastname'])) : ?>
                    <li>
                        <div class="d-flex align-items-center p-2">
                            <!-- You can add the user's profile image here -->
                            <img src="https://cdn1.vectorstock.com/i/1000x1000/23/70/man-avatar-icon-flat-vector-19152370.jpg" alt="User Image" class="rounded-circle me-2" width="40">
                            <div>
                                <strong><?php echo  $_SESSION['firstname'] ; ?><?php echo '&nbsp';?><?php echo $_SESSION['lastname'];?></strong>
                                <br>
                                <?php echo $_SESSION['email']; ?>
                            </div>
                        </div>
                    </li>
                    
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php">Log Out</a></li>
                
                <?php endif; ?>
            </ul>
        </li>
    </ul>
</div>
</nav>
<main class="wrapper">
      
        <!-- =========== Sidebar for admin dashboard =========== -->
        
        <aside id="sidebar" class="show">

            <!-- ======== Content For Sidebar ========-->
            <div class="h-100">
                <!-- ======= Navigation links for sidebar ======== -->
                <ul class="sidebar-nav">
                    <li class="sidebar-header" >
                      MENU 
                    </li>
                    <li class="sidebar-item">
                      <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse" aria-expanded="false">
                        achat/vent
                      </a>
                      <ul  id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                          <a href="order.php" class="sidebar-link">Achats</a>
                        </li>
                        <li class="sidebar-item">
                          <a href="sale.php" class="sidebar-link">Vents</a>
                        </li>
                      </ul>
                    </li>
                    <li class="sidebar-item">
                    <a href="in.php"  class="sidebar-link collapsed"> 
                      Stock
                    </a>
                    </li>
                    <li class="sidebar-header" >
                      maintenance 
                    </li>
                    <li>
                    <a href="client.php" class="sidebar-link collapsed" >
                      Clients
                    </a>
                    </li>
                    <li>
                    <a href="supplier.php" class="sidebar-link collapsed">
                    Fournisseurs
                    </a>
                    </li>
                    <li>
                      <a href="DELIVERYPERSON.php" class="sidebar-link collapsed" >
                      Livreurs
                      </a>
                    </li>
                    <li>
                      <a href="product.php" class="sidebar-link collapsed" >
                       Produits
                      </a>
                    </li>
                  
                </ul>
            </div>
        </aside>
</body>

</html>