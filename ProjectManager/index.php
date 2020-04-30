<style>
    .fobutton {
        border: none;
        margin: 0;
        padding: 0;
        outline: none;
    }
</style>

<?php

//include database
include('db_connect.php');

//query for all employess
$sqlSelect = 'SELECT * FROM employees ORDER BY ID ASC';  //ORDER BY ASC

//make query and get result
$result = mysqli_query($conn, $sqlSelect);

//fetch rows as an array
$employees = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);

//GET request id param

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "SELECT * FROM employees WHERE id = $id";

    $result = mysqli_fetch_all($conn, $sql);

    $employee = mysqli_fetch_assoc($result);

    mysqli_free_result($result);
    mysqli_close($conn);
}

// Update employee logic

if (isset($_POST['oldEmployeeData']) && isset($_POST['newEmployeeData'])) {
    if ($_POST['assignProject'] == "NULL") {
        $stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
        $stmt->bind_param("i", $_POST['oldEmployeeData']);
        $stmt->execute();
        $stmt->close();

        $EmployeeName = $_POST['newEmployeeData'];
        if ($_POST['newEmployeeData'] == "") {
            $EmployeeName = $_POST['oldEmployeeName'];
        }
        $stmt = $conn->prepare("INSERT INTO employees (id, firstname) VALUES (?, ?)");
        $stmt->bind_param("is", $_POST['oldEmployeeData'], $EmployeeName);
        $stmt->execute();
        $stmt->close();
    } else {
        if ($_POST['newEmployeeData'] == "") {
            $EmployeeName = $_POST['oldEmployeeName'];
        } else {
            $EmployeeName = $_POST['newEmployeeData'];
        }
        $assignProject = $_POST['assignProject'];
        $stmt = $conn->prepare("UPDATE employees SET project_id = ?, firstname = ?  WHERE id = ?");
        $stmt->bind_param("isi", $assignProject, $EmployeeName, $_POST['oldEmployeeData']);
        $stmt->execute();
        $stmt->close();
    }
}

// Update project logic
if (isset($_POST['oldProjectData']) && isset($_POST['newProjectData'])) {
    if ($_POST['newProjectData'] == "") {
        $ProjectName = $_POST['oldProjectName'];
    } else {
        $ProjectName = $_POST['newProjectData'];
    }
    $stmt = $conn->prepare("UPDATE projects SET project = ? WHERE id = ?");
    $stmt->bind_param("si", $ProjectName, $_POST['oldProjectData']);
    $stmt->execute();
    $stmt->close();
}

//delete FROM EMPLOYEES table
if (isset($_POST['id_to_delete'])) {
    $stmt = $conn->prepare("DELETE FROM employees WHERE ID = ?");
    $stmt->bind_param("i", $_POST['id_to_delete']);
    $stmt->execute();
    $stmt->close();
}

//delete FROM PROJECTS table
if (isset($_POST['id_to_delete'])) {
    $stmt = $conn->prepare("DELETE FROM projects WHERE ID = ?");
    $stmt->bind_param("i", $_POST['id_to_delete']);
    $stmt->execute();
    $stmt->close();
}

include('header.php');

//display EMPLOYEES table

if (empty($_POST) || isset($_POST['Employees']) || (!isset($_POST['Projects'])) || isset($_POST['employeeToUpdate']) ||
    isset($_POST['newEmployeeData'])) {

    print '<h4 class="center grey-text">Employees</h4>';

    $stmt = $conn->prepare
    ("SELECT employees.id, firstname, project
      FROM employees
      LEFT JOIN projects
      ON employees.project_id = projects.id
      WHERE firstname LIKE ?");
    $stmt->bind_param("s", $a = "%%");
    $stmt->execute();
    $stmt->bind_result($id, $firstname, $project_id);
    echo "<div class='container'>
        <table class='tbl centered '>
        <thead>
        <tr class='tr'>
            <th>ID</th>
            <th>First name</th>
            <th>Projects</th>
            <th>Actions</th>
        </tr>
        </thead>";
    while ($stmt->fetch()) {
        echo "<tr class='tr'>";
        echo "<td>$id</td>";
        echo "<td>$firstname</td>";
        echo "<td>$project_id</td>";
        echo "<td>
        <form method='POST' class='fobutton' style='display: inline-block'>
            <input type='submit' value='Update' class='btn-small brand z-depth-0'>
            <input type='hidden' name='employeeToUpdate' value='" . $id . "'>
        </form>

        <form method='POST' class='fobutton' style='display: inline-block'>
            <input type='submit' value='Delete' class='btn-small red lighten-2 z-depth-0'>
            <input type='hidden' name='id_to_delete' value='" . $id . "'>
        </form>
            </td>";
        echo "</tr>";
    }
    echo "</table>
    </div>";
    $stmt->close();
} else {

    //display PROJECTS table

    print '<h4 class="center grey-text">Projects</h4>';

    $stmt = $conn->prepare
    ("SELECT projects.id, projects.project, group_concat(firstname SEPARATOR ', ')
       AS Employees
       FROM employees RIGHT JOIN projects
       ON employees.project_id = projects.id
       WHERE projects.project LIKE ?
       GROUP BY projects.project, projects.id
       ORDER BY id");
    $stmt->bind_param("s", $a = "%%");
    $stmt->execute();
    $stmt->bind_result($id, $project, $employees);
    echo "<div class='container'>
        <table class='centered'>
        <thead>
        <tr>
            <th>ID</th>
            <th>Project</th>
            <th>Employee</th>
            <th>Actions</th>
        </tr>
        </thead>";
    while ($stmt->fetch()) {
        echo "<tr>";
        echo "<td>$id</td>";
        echo "<td>$project</td>";
        echo "<td>$employees</td>";
        echo "<td>
        <form method='POST' class='fobutton' style='display: inline-block'>
            <input type='submit' value='Update' class='btn-small brand z-depth-0'>
            <input type='hidden' name='projectToUpdate' value='" . $id . "'>
        </form>
        <form method='POST' class='fobutton' style='display: inline-block'>
            <input type='submit' value='Delete' class='btn-small red lighten-2 z-depth-0'>
            <input type='hidden' name='id_to_delete' value='" . $id . "'>
        </form>
        </td>";
        echo "</tr>";
    }
    echo "</table>
    </div>";
    $stmt->close();
}

// Employee update field

if (isset($_POST['employeeToUpdate'])) {
    $stmt = $conn->prepare("SELECT employees.id, firstname, project_id
        FROM employees LEFT JOIN projects ON employees.project_id = projects.id  WHERE employees.id = ?");
    $stmt->bind_param("i", $_POST['employeeToUpdate']);
    $stmt->execute();
    $stmt->bind_result($id, $firstName, $project_id);
    echo "<footer>";
    while ($stmt->fetch()) {
        echo "<form method='POST' id='employeeUpdate'>
        <label for='newProjectName'>Enter new employee name:</label>
        <input type='text' name='newEmployeeData' placeholder='$firstName'>
        <input type='submit' value='Update'  class='btn-small brand z-depth-0'>
        <input type='hidden' name='oldEmployeeData' value='" . $_POST['employeeToUpdate'] . "'>
        <input type='hidden' name='oldEmployeeName' value='$firstName'></form>";

    }
    $stmt->close();

    // Drop down project selection

    echo "<select name='assignProject' form='employeeUpdate'>";
   $sql = "SELECT id, project FROM projects";
   $result = $conn->query($sql);
   $null = false;
   if ($result->num_rows > 0) {
       // output data of each row
       while ($row = $result->fetch_assoc()) {
           if ($project == null && $null == false) {
               $null = true;
           }
           if ($project == $row["project"]) {
               echo "<option value='" . $row["id"] . "' selected>" . $row["project"] . "</option>";
           } else {
               echo "<option value='" . $row["id"]  . "'>" . $row["project"] . "</option>";
           }
       }
   } else {
       echo "0 results";
   }
   if ($null == false) {
       echo "<option value=NULL></option>";
   } else {
       echo "<option value=NULL selected></option>";
   }
   echo "</select></form></footer>";
}

// Project update field

if (isset($_POST['projectToUpdate'])) {
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->bind_param("i", $_POST['projectToUpdate']);
    $stmt->execute();
    $stmt->bind_result($id, $project);

    echo "<footer>";

    while ($stmt->fetch()) {
        echo "<form method='POST'>
        <label for='newProjectName'>Enter new project name:</label>
        <input type='text' name='newProjectData' placeholder='" . $project . "'>
        <input type='submit' value='Update' class='btn-small brand z-depth-0'>
        <input type='hidden' name='oldProjectData' value='" . $_POST['projectToUpdate'] . "'>
        <input type='hidden' name='oldProjectName' value='$project'>
        </form>";
    }
}



include('footer.php');


mysqli_close($conn);
?>
