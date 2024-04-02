<!-- Include authentication script -->
<?php include '../config/auth.php' ?>

<!-- Include header file -->
<?php include '../public/header.php' ?>

<main>

    <h1>Dashboard</h1>
    <div>Attrition Rate
        <!-- Include script to calculate attrition rate -->
        <?php include '../src/attrition_rate.php'; ?>
    </div>

    <div>Avg Employee Tenure
        <!-- Include script to calculate average tenure -->
        <?php include '../src/calculate_tenure.php'; ?>
    </div>

    <div>Total Employees
        <!-- Include the script to calculate average tenure -->
        <?php include '../src/total_employees.php'; ?>
    </div>

    <div>Total Open Positions
        <!-- Include the script to calculate average tenure -->
        <?php include '../src/calculate_positions.php'; ?>
    </div>

    <div class="headcount_section">
        <b>Headcount by Location</b>

        <button id="exportHeadcount">Export to Excel</button><br><br>

        <form method="get" action="" id="locationFilterForm">
            <label for="locationFilter">Filter by Location:</label>
            <select name="locationFilter" id="locationFilter">
                <option value="">All Locations</option>
                <!-- Include the script to filter headcount data -->
                <?php include '../src/headcount_location_filter.php'; ?>

            </select>

            <input type="submit" value="Apply Filter">
        </form>

        <div id="headcountResults">
            <!-- The results will be displayed here -->
        </div>

        <script>
            $(document).ready(function () {
                // Handle form submission using jQuery
                $('#locationFilterForm').submit(function (event) {
                    event.preventDefault(); // Prevent the default form submission

                    // Fetch the selected value from the dropdown
                    var locationFilter = $('#locationFilter').val();

                    // Make an AJAX request to the server
                    $.ajax({
                        type: 'GET',
                        url: '../src/headcount_by_location.php',
                        data: { locationFilter: locationFilter },
                        success: function (response) {
                            // Update the content of the results div with the server response
                            $('#headcountResults').html(response);
                        },
                        error: function (error) {
                            console.log('Error:', error);
                        }
                    });

                    return false; // Ensure the form is not submitted again
                });

                // Handle export button click
                $('#exportHeadcount').click(function () {
                    // Fetch the selected value from the dropdown
                    var locationFilter = $('#locationFilter').val();

                    // Make a request to download the Excel file
                    var downloadUrl = '../src/headcount_by_location.php?locationFilter=' + locationFilter;

                    // Trigger the file download using a hidden anchor element
                    var link = document.createElement('a');
                    link.href = downloadUrl;
                    link.download = 'headcount_data.xlsx';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            });
        </script>


    </div>

    <div class="open_positions_section"> <b>Open Positions</b>

        <button id="exportCostCenters">Export to Excel</button><br><br>

        <!-- Open Positions Form -->

        <form method="get" action="" id="openRolesFilterForm">
            <label for="openRolesCostCenterFilter">Filter by Cost Center:</label>
            <select name="openRolesCostCenterFilter" id="openRolesCostCenterFilter">
                <option value="">All Cost Centers</option>
                <!-- Include the script to filter cost center data -->
                <?php include '../src/cost_center_filter.php'; ?>
            </select>

            <input type="submit" value="Apply Filter">
        </form>

        <div id="openRolesResults">
            <!-- The results will be displayed here -->
        </div>

        <script>
            $(document).ready(function () {
                // Handle form submission for open positions per cost center using jQuery
                $('#openRolesFilterForm').submit(function (event) {
                    event.preventDefault(); // Prevent the default form submission

                    // Fetch the selected value from the dropdown
                    var openRolesCostCenterFilter = $('#openRolesCostCenterFilter').val();

                    // Make an AJAX request to the server for open positions per cost center
                    $.ajax({
                        type: 'GET',
                        url: '../src/positions_per_costcenter.php',
                        data: { openRolesCostCenterFilter: openRolesCostCenterFilter },
                        success: function (response) {
                            // Update the content of the results div with the server response
                            $('#openRolesResults').html(response);
                        },
                        error: function (error) {
                            console.log('Error:', error);
                        }
                    });

                    return false; // Ensure the form is not submitted again
                });

                // Handle export button click
                $('#exportCostCenters').click(function () {
                    // Fetch the selected value from the dropdown
                    var openRolesCostCenterFilter = $('#openRolesCostCenterFilter').val();

                    // Make a request to download the Excel file
                    var downloadUrl = '../src/positions_per_costcenter.php?openRolesCostCenterFilter=' + openRolesCostCenterFilter;

                    // Trigger the file download using a hidden anchor element
                    var link = document.createElement('a');
                    link.href = downloadUrl;
                    link.download = 'openpositions_data.xlsx';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });


            });
        </script>
    </div>

</main>

<footer>&copy; 2023-2024 Kelsey Rajher. All Rights Reserved.</footer>
</div>
</body>


</html>