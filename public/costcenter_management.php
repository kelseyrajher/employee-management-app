<?php
// Include authentication script
include '../config/auth.php' ?>

<!-- Include header file -->
<?php include '../public/header.php' ?>

<main class="management_pages">
    <h1>Cost Center Management </h1>
    <div>
        <h2>All Cost Centers</h2>
        <a href="../public/add_costcenter.php">
            <button type="submit" name="add_costcenter">Add New Cost Center</button></a><br><br>
    </div>
    <div class=scrollable-table>
        <table id="myTable" class="tablesorter display-table">
            <thead>
                <tr>
                    <th>Cost Center ID <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Name <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Location <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th data-sorter="false">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch data from the database and display cost centers in the table
                include '../config/db.php';

                // Using prepared statement to query cost center table
                $stmtFetchCostCenters = $conn->prepare("SELECT cost_center_id, cost_center_name, location FROM costcenter");
                $stmtFetchCostCenters->execute();
                $result_cost_centers = $stmtFetchCostCenters->get_result();

                // Loop through the database results and display cost centers in the table
                while ($costCenter = $result_cost_centers->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($costCenter['cost_center_id'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($costCenter['cost_center_name'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td>" . htmlspecialchars($costCenter['location'], ENT_QUOTES, 'UTF-8') . "</td>";
                    echo "<td><a href='../public/edit_costcenter.php?cost_center_id=" . htmlspecialchars($costCenter['cost_center_id'], ENT_QUOTES, 'UTF-8') . "'>Edit</a> | <a href='#' onclick=\"confirmDeleteCostCenter('" . htmlspecialchars($costCenter['cost_center_name'], ENT_QUOTES, 'UTF-8') . "', '../src/delete_costcenter.php?cost_center_id=" . htmlspecialchars($costCenter['cost_center_id'], ENT_QUOTES, 'UTF-8') . "')\">Delete</a></td>";
                    echo "</tr>";
                }

                // Close the prepared statement
                $stmtFetchCostCenters->close();

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