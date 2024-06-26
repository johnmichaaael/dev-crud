<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 900px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        </ul>

        <!-- Move this entire dropdown li to the right -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Panel
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="../dashboard.php">Dashboard</a>
                    <a class="dropdown-item" href="#">Employees</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-warning" href="../reset-password.php">Reset Your Password</a>
                    <a class="dropdown-item text-danger" href="../logout.php">Sign Out of Your Account</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Employees Details</h2>
                        <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Employee</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "../db/config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM employees";
                    if($result = $pdo->query($sql)){
                     if($result->rowCount() > 0){
                          echo '<table class="table table-bordered table-striped">';
                              echo "<thead>";
                                   echo "<tr>";
                                       echo "<th>#</th>";
                                       echo "<th>Last Name</th>";
                                       echo "<th>First Name</th>";
                                       //echo "<th>Middle Name</th>";
                                       echo "<th>Address</th>";
                                       // echo "<th>Salary</th>";
                                       echo "<th>Year Level</th>";
                                       echo "<th>Time In</th>";
                                       echo "<th>Action</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = $result->fetch()){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                         echo "<td>" . $row['last_name'] . "</td>";
                                         echo "<td>" . $row['first_name'] . "</td>";
                                         // echo "<td>" . $row['middle_name'] . "</td>";
                                         echo "<td>" . $row['address'] . "</td>";
                                         // echo "<td>" . $row['salary'] . "</td>";
                                         echo "<td>" . $row['year_level'] . "</td>"; 
                                         echo "<td>" . $row['time'] . "</td>";
                                         echo "<td>";
                                         echo '<a href="read.php?id='. $row['id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                         echo '<a href="update.php?id='. $row['id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                         echo '<a href="delete.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                     echo "</td>";
                                 echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                             // Free result set
                             unset($result);
                         } else{
                             echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                         }
                            } else{
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                    
                    // Close connection
                    unset($pdo);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>