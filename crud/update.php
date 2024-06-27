<?php
// Include config file
require_once "../db/config.php";
 
// Define variables and initialize with empty values
$last_name = $first_name = $middle_name = $address = $salary = $year_level = "";
$last_name_err = $first_name_err = $middle_name_err = $address_err = $salary_err = $year_level_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate last name
    $input_last_name = trim($_POST["last_name"]);
    if(empty($input_last_name)){
        $last_name_err = "Please enter a last name.";
    } elseif(!filter_var($input_last_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $last_name_err = "Please enter a valid name.";
    } else{
        $last_name = $input_last_name;
    }

    // Validate first name
    $input_first_name = trim($_POST["first_name"]);
    if(empty($input_first_name)){
        $first_name_err = "Please enter a first name.";
    } elseif(!filter_var($input_first_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $first_name_err = "Please enter a valid name.";
    } else{
        $first_name = $input_first_name;
    }
    
    // Validate middle name
    $input_middle_name = trim($_POST["middle_name"]);
    if(empty($input_middle_name)){
        $middle_name_err = "Please enter a middle name.";
    } elseif(!filter_var($input_middle_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $middle_name_err = "Please enter a valid name.";
    } else{
        $middle_name = $input_middle_name;
    }
    
    // Validate address address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate salary
    $input_salary = trim($_POST["salary"]);
    if(empty($input_salary)){
        $salary_err = "Please enter the salary amount.";     
    } elseif(!ctype_digit($input_salary)){
        $salary_err = "Please enter a positive integer value.";
    } else{
        $salary = $input_salary;
    }

    // Validate year_level
    $input_year_level = trim($_POST["year_level"]);
    if(empty($input_year_level) || $input_year_level == "Choose..."){
        $year_level_err = "Please select a year level.";
    } else {
        $year_level = $input_year_level;
    }
    
    // Check input errors before inserting in database
    if(empty($last_name_err) && empty($first_name_err) && empty($middle_name_err) && empty($address_err) && empty($salary_err)&& empty($year_level_err)){
        // Prepare an update statement
        $sql = "UPDATE employees SET last_name=:last_name, first_name=:first_name, middle_name=:middle_name, address=:address, salary=:salary, year_level=:year_level WHERE id=:id";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":id", $param_id);
            $stmt->bindParam(":last_name", $param_last_name);
            $stmt->bindParam(":first_name", $param_first_name);
            $stmt->bindParam(":middle_name", $param_middle_name);
            $stmt->bindParam(":address", $param_address);
            $stmt->bindParam(":salary", $param_salary);
            $stmt->bindParam(":year_level", $param_year_level);
            
            // Set parameters
            $param_id = $id;
            $param_last_name = $last_name;
            $param_first_name = $first_name;
            $param_middle_name = $middle_name;
            $param_address = $address;
            $param_salary = $salary;
            $param_year_level = $year_level;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: crud.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM employees WHERE id = :id";
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":id", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                    // Retrieve individual field value
                    $last_name = $row["last_name"];
                    $first_name = $row["first_name"];
                    $middle_name = $row["middle_name"];
                    $address = $row["address"];
                    $salary = $row["salary"];
                    $year_level = $row["year_level"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        unset($stmt);
        
        // Close connection
        unset($pdo);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                            <span class="invalid-feedback"><?php echo $last_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                            <span class="invalid-feedback"><?php echo $first_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" name="middle_name" class="form-control <?php echo (!empty($middle_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $middle_name; ?>">
                            <span class="invalid-feedback"><?php echo $middle_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Salary</label>
                            <input type="text" name="salary" class="form-control <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary; ?>">
                            <span class="invalid-feedback"><?php echo $salary_err;?></span>
                        </div>
                        <div class="form-group">
                            <label for="inputState">Year Level</label>
                            <select id="inputState" name="year_level" class="form-control <?php echo (!empty($year_level_err)) ? 'is-invalid' : ''; ?>">
                                <option value="" <?php echo ($year_level == '') ? 'selected' : ''; ?>>Choose...</option>
                                <option value="First Year" <?php echo ($year_level == 'First Year') ? 'selected' : ''; ?>>First Year</option>
                                <option value="Second Year" <?php echo ($year_level == 'Second Year') ? 'selected' : ''; ?>>Second Year</option>
                                <option value="Third Year" <?php echo ($year_level == 'Third Year') ? 'selected' : ''; ?>>Third Year</option>
                                <option value="Fourth Year" <?php echo ($year_level == 'Fourth Year') ? 'selected' : ''; ?>>Fourth Year</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $year_level_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="crud.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>