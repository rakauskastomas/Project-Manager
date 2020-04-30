<?php
// -----------------------------------------------------------------
//include database
include('db_connect.php');
// -----------------------------------------------------------------

$projectName = '';
$errors = array('projectName' => '');

if (isset($_POST['submit'])) {
    //check projectName
    if (empty($_POST['projectName'])) {
        $errors['projectName'] = 'Project name is required <br />';
    } else {
        echo htmlspecialchars($_POST['projectName']);
    }

    if(array_filter($errors)){
        //echo 'There are errors in the form';
    } else {
        $projectName = mysqli_real_escape_string($conn, $_POST['projectName']);

        //create sql
        $sql = "INSERT INTO projects(project) VALUES('$projectName')";

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
    <h4 class="center">Add Project</h4>
    <form action="addProject.php" class="white" method="POST">
        <label for="">Project name: </label>
        <input type="text" name="projectName" value="<?php echo htmlspecialchars($projectName); ?>">
            <div class="red-text"><?php echo $errors['projectName']; ?></div>


        <div class="center">
            <input type="submit" name="submit" value="submit" class="btn brand z-depth-0">
        </div>
    </form>
</section>

<?php include('footer.php'); ?>

</html>
