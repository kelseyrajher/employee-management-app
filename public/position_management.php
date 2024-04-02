<?php
// Include authentication script
include '../config/auth.php'
    ?>

<!-- Include header file -->
<?php include '../public/header.php' ?>

<main class="management_pages">
    <h1>Position Management</h1>
    <div>
        <h2>All Positions</h2>
        <a href="add_position.php">
            <button type="submit" name="add_costcenter">Add Position</button></a><br><br>
    </div>

    <div class=scrollable-table>
        <table id="myTable" class="tablesorter display-table" data-sortlist="[[5,1]]">
            <thead>
                <tr>
                    <th>Position ID <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Job Name <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Cost Center <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Location <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Line Code <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                    <th>Status <i class="fa-solid fa-arrows-up-down" style="color: white"></i></th>
                </tr>
            </thead>
            <?php
            // Fetch data from the database and display positions in the table
            include '../config/db.php';

            $stmtFetchPositions = $conn->prepare("SELECT position.*, roles.line_code, roles.job_name, costcenter.location, costcenter.cost_center_name
            FROM position
            INNER JOIN roles ON position.job_id = roles.role_id
            INNER JOIN costcenter ON position.cost_center_id = costcenter.cost_center_id");
            $stmtFetchPositions->execute();
            $result_position = $stmtFetchPositions->get_result();

            // Loop through the database results and display positions in the table
            while ($position = $result_position->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($position['position_id'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($position['job_name'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($position['cost_center_name'], ENT_QUOTES, 'UTF-8') . "</td>"; // Display cost center name instead of id
                echo "<td>" . htmlspecialchars($position['location'], ENT_QUOTES, 'UTF-8') . "</td>"; 
                echo "<td>" . htmlspecialchars($position['line_code'], ENT_QUOTES, 'UTF-8') . "</td>";
                // Display "Closed" if is_filled is true, else display "Open"
                echo "<td>";
                echo $position['is_filled'] ? '<span style="color:red;">Closed</span>' : '<span style="color:green;">Open</span>';
                echo "</td>";

                echo "</tr>";
            }

             // Close the prepared statement
             $stmtFetchPositions->close();

             // Close the database connection
             $conn->close();
            ?>


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