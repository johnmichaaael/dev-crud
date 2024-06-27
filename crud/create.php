<?php
// Include config file
require_once "../db/config.php";

// Flag to check if the form was submitted successfully
$form_submitted = false;
$duplicate_record = false;
 
// Define variables and initialize with empty values
$last_name = $first_name = $middle_name = $address = $salary = $year_level = "";
$last_name_err = $first_name_err = $middle_name_err = $address_err = $salary_err = $year_level_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
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
    
    // Validate address
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
        
        // Prepare an insert statement
        // $sql = "INSERT INTO employees (last_name, first_name, middle_name, address, salary, year_level) VALUES (:last_name, :first_name, :middle_name, :address, :salary, :year_level)";
        
         // Check if the record already exists
         $sql = "SELECT COUNT(*) FROM employees WHERE last_name =:last_name AND first_name =:first_name AND middle_name =:middle_name";

        if($stmt = $pdo->prepare($sql)){
            
            // Set parameters
            $param_last_name = $last_name;
            $param_first_name = $first_name;
            $param_middle_name = $middle_name;
        


            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":last_name", $param_last_name);
            $stmt->bindParam(":first_name", $param_first_name);
            $stmt->bindParam(":middle_name", $param_middle_name);
    
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if ($stmt->fetchColumn() > 0) {
                    // Duplicate record found
                    $duplicate_record = true;
                } else {

                    $sql = "INSERT INTO employees (last_name, first_name, middle_name, address, salary, year_level) VALUES (:last_name, :first_name, :middle_name, :address, :salary, :year_level)";

                    if ($stmt = $pdo->prepare($sql)) {
                        // Set parameters
                        $param_last_name = $last_name;
                        $param_first_name = $first_name;
                        $param_middle_name = $middle_name;
                        $param_address = $address;
                        $param_salary = $salary;
                        $param_year_level = $year_level;

                        // Bind variables to the prepared statement as parameters
                        $stmt->bindParam(":last_name", $param_last_name);
                        $stmt->bindParam(":first_name", $param_first_name);
                        $stmt->bindParam(":middle_name", $param_middle_name);
                        $stmt->bindParam(":address", $param_address);
                        $stmt->bindParam(":salary", $param_salary);
                        $stmt->bindParam(":year_level", $param_year_level);

                        // Attempt to execute the prepared statement
                        if ($stmt->execute()) {
                            // Set the form submission flag to true
                            $form_submitted = true;
                            $last_name = $first_name = $middle_name =  $address = $salary = $year_level = "";
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                    }    
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        .toast-container {
            position: fixed;
            top: 25%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label> Last Name</label>
                            <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>" placeholder="Enter last name" >

                            <span class="invalid-feedback"><?php echo $last_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>" placeholder="Enter first name">
                            <span class="invalid-feedback"><?php echo $first_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" name="middle_name" class="form-control <?php echo (!empty($middle_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $middle_name; ?>" placeholder="Enter middle name">
                            <span class="invalid-feedback"><?php echo $middle_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" placeholder="Enter address"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Salary</label>
                            <input type="text" name="salary" class="form-control <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary; ?>" placeholder="Enter salary">
                            <span class="invalid-feedback"><?php echo $salary_err;?></span>
                        </div>
                        <div class="form-group">
                            <label for="inputState">Year Level</label>
                            <select id="inputState" name="year_level" class="form-control <?php echo (!empty($year_level_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $year_level; ?>">
                                <option value="" selected>Choose...</option>
                                <option value="First Year">First Year</option>
                                <option value="Second Year">Second Year</option>
                                <option value="Third Year">Third Year</option>
                                <option value="Fourth Year">Fourth Year</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $year_level_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="crud.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
    <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Toast HTML -->
<div class="toast-container">
   <div id="successToast" class="toast text-bg-success" role="alert" aria-live="assertive" aria-atomic="true">
       <div class="toast-header">
           <strong class="me-auto">Success</strong>
           <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
       </div>
       <div class="toast-body">
       New record added successfully!
       </div>
   </div>

   <div id="duplicateToast" class="toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
       <div class="toast-header">
           <strong class="me-auto">Duplicate</strong>
           <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
       </div>
       <div class="toast-body">
           Duplicate record found. No new record added.
       </div>
   </div>
</div>

<!-- Trigger Toast JS -->
<?php if ($form_submitted): ?>
   <script type="text/javascript">
       var successToastEl = document.getElementById('successToast');
       var successToast = new bootstrap.Toast(successToastEl);
       successToast.show();
       document.getElementById('facultyForm').reset();
   </script>
<?php elseif ($duplicate_record): ?>
   <script type="text/javascript">
       var duplicateToastEl = document.getElementById('duplicateToast');
       var duplicateToast = new bootstrap.Toast(duplicateToastEl);
       duplicateToast.show();
   </script>
<?php endif; ?>
</body>
</html>