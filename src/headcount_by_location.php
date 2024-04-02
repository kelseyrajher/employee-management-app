<?php
// Include db.php and other necessary files
include '../config/db.php';
require '../vendor/autoload.php'; // Include the PhpSpreadsheet autoloader

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Ensure the locationFilter parameter is set
if (isset($_GET['locationFilter'])) {
    // Retrieve the location filter from the AJAX request
    $locationFilter = $_GET['locationFilter'];

    // Query to get headcount by location with the added WHERE clause
    $headcountByLocationQuery = "SELECT cc.cost_center_name, cc.location, cc.expected_headcount, COUNT(DISTINCT i.employee_id) AS headcount
                                FROM Incumbents i
                                JOIN CostCenter cc ON i.cost_center_id = cc.cost_center_id";

    // Append WHERE clause for location filter, if provided
    if (!empty($locationFilter)) {
        $headcountByLocationQuery .= " WHERE cc.location LIKE ?";
    }

    $headcountByLocationQuery .= " GROUP BY cc.cost_center_name, cc.location, cc.expected_headcount";

    $stmtHeadcountByLocation = $conn->prepare($headcountByLocationQuery);

    if (!$stmtHeadcountByLocation) {
        die("Error in preparing headcount by location query: " . $conn->error);
    }

    // Bind the parameter for the location filter, if applicable
    if (!empty($locationFilter)) {
        $locationFilter = '%' . $locationFilter . '%'; // Add wildcard characters for partial matching
        $stmtHeadcountByLocation->bind_param('s', $locationFilter);
    }

    $stmtHeadcountByLocation->execute();
    $resultHeadcountByLocation = $stmtHeadcountByLocation->get_result();

    // Check for a successful query for headcount by location
    if ($resultHeadcountByLocation) {
        // If an AJAX request, return the results as HTML
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            while ($rowHeadcountByLocation = $resultHeadcountByLocation->fetch_assoc()) {
                echo "Cost Center Name: " . htmlspecialchars($rowHeadcountByLocation['cost_center_name'], ENT_QUOTES, 'UTF-8') . "<br>";
                echo "Location: " . htmlspecialchars($rowHeadcountByLocation['location'], ENT_QUOTES, 'UTF-8') . "<br>";
                echo "Current Headcount: " . htmlspecialchars($rowHeadcountByLocation['headcount'], ENT_QUOTES, 'UTF-8') . "<br>";
                echo "Expected Headcount: " . htmlspecialchars($rowHeadcountByLocation['expected_headcount'], ENT_QUOTES, 'UTF-8') . "<br><br>";
            }
        } else {
            // If a direct request, generate Excel and send headers
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Cost Center Name');
            $sheet->setCellValue('B1', 'Location');
            $sheet->setCellValue('C1', 'Current Headcount');
            $sheet->setCellValue('D1', 'Expected Headcount');

            $row = 2;

            while ($rowHeadcountByLocation = $resultHeadcountByLocation->fetch_assoc()) {
                $sheet->setCellValue('A' . $row, $rowHeadcountByLocation['cost_center_name']);
                $sheet->setCellValue('B' . $row, $rowHeadcountByLocation['location']);
                $sheet->setCellValue('C' . $row, $rowHeadcountByLocation['headcount']);
                $sheet->setCellValue('D' . $row, $rowHeadcountByLocation['expected_headcount']);
                $row++;
            }

            $excelFileName = 'headcount_data.xlsx';
            $writer = new Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $excelFileName . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        }
    } else {

        // Handle database query error
        die("Error in headcount by location query: " . $stmtHeadcountByLocation->error);
    }

    // Close the statement
    $stmtHeadcountByLocation->close();
}
// Close the database connection
$conn->close();

exit(); 

?>