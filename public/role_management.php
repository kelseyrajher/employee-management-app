<?php
// Include authentication script
include '../config/auth.php'
    ?>

<!-- Include header file -->
<?php include '../public/header.php' ?>

<main class="management_pages">
    <h1>Role Management </h1>
    <div>
        <h2>All Roles</h2>
        <a href="add_role.php">
            <button type="submit" name="add_costcenter">Add New Role</button></a><br><br>
    </div>
    <div class=scrollable-table>
        <table id="myTable" class="tablesorter display-table">
            <thead>
                <tr>
                    <th>Role ID <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Job Name <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Line Code <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th data-sorter="false">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch data from the database and display roles in the table
                include '../config/db.php';

                // Using prepared statement to prevent SQL injection
                $stmtFetchRoles = $conn->prepare("SELECT * FROM roles");
                $stmtFetchRoles->execute();
                $result_roles = $stmtFetchRoles->get_result();

                // Loop through the database results and display roles in the table
                while ($role = $result_roles->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($role['role_id'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($role['job_name'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($role['line_code'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td><a href='edit_role.php?role_id=" . htmlspecialchars($role['role_id'], ENT_QUOTES, 'UTF-8') . "'>Edit</a> | <a href='#' onclick=\"confirmDeleteRole('" . htmlspecialchars($role['job_name'], ENT_QUOTES, 'UTF-8') . "', 'delete_role.php?role_id=" . htmlspecialchars($role['role_id'], ENT_QUOTES, 'UTF-8') . "')\">Delete</a></td>";
                    echo "</tr>";

                }
                // Close the prepared statement
                $stmtFetchRoles->close();

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
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