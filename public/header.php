<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management Dashboard</title>
    <script src="https://kit.fontawesome.com/2b0ddb19cc.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="active_navigation.js"></script>
    <script src="script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js"
        integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/additional-methods.min.js"
        integrity="sha512-TiQST7x/0aMjgVTcep29gi+q5Lk5gVTUPE9XgN0g96rwtjEjLpod4mlBRKWHeBcvGBAEvJBmfDqh2hfMMmg+5A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/tablesorter@2.31.3/dist/js/jquery.tablesorter.combined.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tablesorter@2.31.3/dist/js/jquery.tablesorter.combined.min.js"></script>
</head>

<body>

    <div class="grid-container">
        <header>
            <img src="../images/logoipsum-white.svg" alt="company logo">
            <p>Welcome,
                <?php echo htmlspecialchars($first_name) . " " . htmlspecialchars($last_name); ?>
                <br>
                <a href="../src/logout.php"><button class="logout_button" type="button"
                        name="add_costcenter">Logout</button></a>
            </p>

        </header>

        <nav>
            <ul>
                <li>
                    <a href="dashboard.php" class="navlink">
                        <i class="fa-solid fa-gauge"></i>
                        <span> Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="costcenter_management.php">
                        <i class="fa-solid fa-building"></i> Cost Centers</a>
                </li>
                <li>
                    <a href="employee_management.php" class="navlink">
                        <i class="fa-solid fa-user-tie"></i> Employees</a>
                </li>
                <li>
                    <a href="role_management.php" class="navlink">
                        <i class="fa-solid fa-users-gear"></i> Roles</a>
                </li>
                <li>
                    <a href="position_management.php" class="navlink">
                        <i class="fa-solid fa-briefcase"></i> Positions</a>
                </li>
            </ul>
        </nav>