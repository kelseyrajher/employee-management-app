<?php
include '../config/auth.php'
    ?>

<!-- Include header file -->
<?php include '../public/header.php' ?>

<main class="management_pages">
    <h1>Employee Management</h1>
    <div>
        <h2>All Employees</h2>
        <a href="add_employee.php">
            <button type="submit" name="add_costcenter">Add Employee</button></a><br><br>

    </div>
    <div class=scrollable-table>
        <table id="myTable" class="tablesorter display-table" data-sortlist="[[4,0]]">
            <thead>
                <tr>
                    <th>First Name <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Last Name <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Cost Center ID <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Position ID <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Status <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th data-sorter="false">Action </th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch data from the database and display employees in the table
                include '../config/db.php';

                $stmtFetchEmployees = $conn->prepare("SELECT * FROM incumbents");
                $stmtFetchEmployees->execute();
                $result_incumbents = $stmtFetchEmployees->get_result();

                // Loop through the database results and display employees in the table
                while ($incumbent = $result_incumbents->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($incumbent['first_name'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($incumbent['last_name'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($incumbent['cost_center_id'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($incumbent['position_id'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>";
                    if ($incumbent['end_date'] != null) {
                        echo '<span style="color:red;">Inactive/Terminated</span>';
                    } else {
                        echo '<span style="color:green;">Active</span>';
                    }

                    echo "</td>";
                    echo "<td><a href='edit_employee.php?employee_id=" . htmlspecialchars($incumbent['employee_id'], ENT_QUOTES, 'UTF-8') . "'>Edit</a></td>";
                    echo "</tr>";

                }

                // Close the prepared statement
                $stmtFetchEmployees->close();

                // Close the database connection
                $conn->close();
                ?>
            <tbody>
        </table>
    </div>
</main>

<footer>&copy; 2023-2024 Kelsey Rajher. All Rights Reserved.</footer>
</div>
<script>$(function () {
        $("#myTable").tablesorter();
    });
</script>




</body>


</html>