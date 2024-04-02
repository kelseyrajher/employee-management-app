<?php
// Include db.php and other necessary files
include '../config/db.php';
require '../vendor/autoload.php'; // Include the PhpSpreadsheet autoloader

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_GET['openRolesCostCenterFilter'])) {
    // Retrieve the open roles cost center filter from the form submission
    $openRolesCostCenterFilter = $_GET['openRolesCostCenterFilter'];

    // Query to get open roles count by cost center with the added WHERE clause
    $openRolesQuery = "SELECT
                    cc.cost_center_name,
                    cc.location,
                    COUNT(DISTINCT CASE WHEN p.is_filled = 0 THEN p.position_id END) AS open_roles_count
                FROM
                    CostCenter cc
                    LEFT JOIN Incumbents i ON cc.cost_center_id = i.cost_center_id
                    LEFT JOIN Position p ON i.position_id = p.position_id";

    // Append WHERE clause for cost center filter, if provided
    if (!empty($openRolesCostCenterFilter)) {
        $openRolesQuery .= " WHERE cc.cost_center_name LIKE ?";
    }

    $openRolesQuery .= " GROUP BY cc.cost_center_name, cc.location";

    $stmtOpenRoles = $conn->prepare($openRolesQuery);

    if (!$stmtOpenRoles) {
        die("Error in preparing open roles query: " . $conn->error);
    }

    // Bind the parameter for the cost center filter, if applicable
    if (!empty($openRolesCostCenterFilter)) {
        $openRolesCostCenterFilter = '%' . $openRolesCostCenterFilter . '%'; // Add wildcard characters for partial matching
        $stmtOpenRoles->bind_param('s', $openRolesCostCenterFilter);
    }

    $stmtOpenRoles->execute();
    $resultOpenRoles = $stmtOpenRoles->get_result();


    // Check for a successful query for open roles
    if ($resultOpenRoles) {


        // If an AJAX request, return the results as HTML
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            while ($rowOpenRoles = $resultOpenRoles->fetch_assoc()) {
                echo "Cost Center Name: " . htmlspecialchars($rowOpenRoles['cost_center_name'], ENT_QUOTES, 'UTF-8') . "<br>";
                echo "Location: " . htmlspecialchars($rowOpenRoles['location'], ENT_QUOTES, 'UTF-8') . "<br>";
                echo "Number of Open Positions: " . htmlspecialchars($rowOpenRoles['open_roles_count'], ENT_QUOTES, 'UTF-8') . "<br><br>";
            }
        } else {


            // If a direct request, generate Excel and send headers
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Cost Center Name');
            $sheet->setCellValue('B1', 'Location');
            $sheet->setCellValue('C1', 'Number of Open Positions');

            $row = 2;

            while ($rowOpenRoles = $resultOpenRoles->fetch_assoc()) {
                $sheet->setCellValue('A' . $row, $rowOpenRoles['cost_center_name']);
                $sheet->setCellValue('B' . $row, $rowOpenRoles['location']);
                $sheet->setCellValue('C' . $row, $rowOpenRoles['open_roles_count']);
                $row++;
            }

            $excelFileName = 'openpositions_data.xlsx';
            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $excelFileName . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        }
    } else {

        // Handle database query error 
        die("Error in open roles query: " . $stmtOpenRoles->error);
    }

    $stmtOpenRoles->close();

}

// Close the database connection
$conn->close();

exit();

?>