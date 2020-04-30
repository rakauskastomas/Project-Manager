<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- compiled and minified CSS  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <title>Project Manager</title>

    <style type="text/css">
        .brand {
            background: #cbb09c !important;
        }

        .brand-text {
            color: #cbb09c !important;
        }

        form {
            max-width: 460px;
            margin: 20px auto;
            padding: 20px;
        }
    </style>
</head>

<body class="grey lighten-4">

    <nav class="white z-depth-0">
        <div class="container">
            <a href="index.php" class="brand-logo brand-text">Project Manager</a>
            <ul id="nav-mobile" class="right hide-on-small-and-down">
                <li><a href="addEmployee.php" class="btn brand z-depth-0">Add Employee</a></li>
                <li><a href="addProject.php" class="btn brand z-depth-0">Add Project</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <form class="col s12 m4 l2" action='index.php' method='POST'>
                <div class="row">
                    <div class="input-field col s6">
                        <input type='submit' name='Employees' value='Employees' id="first_name" class="btn brand z-depth-0">
                    </div>
                </div>
            </form>
            <form class="col s12 m4 l2" action='index.php' method='POST'>
                <div class="row">
                    <div class="input-field col s6">
                        <input type='submit' name='Projects' value='Projects' id="first_name" class="btn brand z-depth-0">
                    </div>
                </div>
            </form>
        </div>
    </div>

