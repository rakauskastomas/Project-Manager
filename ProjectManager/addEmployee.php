<?php
// -----------------------------------------------------------------
//include database
include('db_connect.php');
// -----------------------------------------------------------------

$firstName = '';
$errors = array('firstName' => '');

if (isset($_POST['submit'])) {
    //check firstName
    if (empty($_POST['firstName'])) {
        $errors['firstName'] = 'First name is required <br />';
    } else {
        $firstName = $_POST['firstName'];
        if(!preg_match('/^[a-zA-Z\s]+$/', $firstName)){
            $errors['firstName'] = 'Name must be letters and spaces only';
        }
    }

    if(array_filter($errors)){
        //echo 'There are errors in the form';
    } else {
        $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);

        //create sql
        $sql = "INSERT INTO employees(firstname) VALUES('$firstName')";

        //save to db and check
        if(mysqli_query($conn, $sql)){
            //success
            header('Location: index.php');
        } else {
            //error
            echo 'Query error: ' . mysqli_error(($conn));
        }
    }

} //end of the POST check
?>

<!DOCTYPE html>
<html lang="en">

<?php include('header.php'); ?>

<section class="container grey-text">
    <h4 class="center">Add Employee</h4>
    <form action="addEmployee.php" class="white" method="POST">
        <label for="">First name: </label>
        <input type="text" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>">
            <div class="red-text"><?php echo $errors['firstName']; ?></div>

        <div class="center">
            <input type="submit" name="submit" value="submit" class="btn brand z-depth-0">
        </div>
    </form>
</section>

<?php include('footer.php'); ?>



</html>
