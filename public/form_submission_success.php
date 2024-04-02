<?php
// Include authentication script
include '../config/auth.php' ?>

<?php
// Include header file 
include '../public/header.php' ?>


<?php
// Check if the type is specified in the URL
if (isset($_GET['type'])) {
    $type = $_GET['type'];

    // Customized success message and action buttons based on the type
    switch ($type) {
        case 'add_costcenter':
            $message = "Cost center added successfully!";
            $returnUrl = "costcenter_management.php";
            $returnFormUrl = "add_costcenter.php";
            $returnLabel = "Return to Cost Centers Page";
            $returnFormLabel = "Add Another Cost Center";
            break;

        case 'add_employee':
            $message = "Employee added successfully!";
            $returnUrl = "employee_management.php";
            $returnFormUrl = "add_employee.php";
            $returnLabel = "Return to Employees Page";
            $returnFormLabel = "Add Another Employee";
            break;

        case 'add_role':
            $message = "Role added successfully!";
            $returnUrl = "role_management.php";
            $returnFormUrl = "add_role.php";
            $returnLabel = "Return to Roles Page";
            $returnFormLabel = "Add Another Role";
            break;

        case 'add_position':
            $message = "Position added successfully!";
            $returnUrl = "position_management.php";
            $returnFormUrl = "add_position.php";
            $returnLabel = "Return to Positions Page";
            $returnFormLabel = "Add Another Position";
            break;

        case 'edit_costcenter':
            $message = "Cost center updated successfully!";
            $returnUrl = "costcenter_management.php";
            $returnFormUrl = "";
            $returnLabel = "Return to Cost Centers Page";
            $returnFormLabel = "";
            break;

        case 'edit_employee':
            $message = "Employee updated successfully!";
            $returnUrl = "employee_management.php";
            $returnFormUrl = "";
            $returnLabel = "Return to Employees Page";
            $returnFormLabel = "";
            break;

        case 'edit_role':
            $message = "Role updated successfully!";
            $returnUrl = "role_management.php";
            $returnFormUrl = "";
            $returnLabel = "Return to Roles Page";
            $returnFormLabel = "";
            break;
    }
} else {

    // Default message and URLs
    $message = "Form submitted successfully!";
    $returnUrl = "dashboard.php";
    $$returnFormUrl = "";
    $returnLabel = "Return to Home Page";
    $returnFormLabel = "";
}
?>

<main class="form_success_message management_pages">
    <div></div>
    <div></div>
    <div>
        <h1><i class="fa-solid fa-circle-check"></i><br>
            <?php echo $message; ?>
        </h1><br>
        <button onclick="window.location.href='<?php echo $returnUrl; ?>'">
            <?php echo $returnLabel; ?>
        </button>
        <?php if ($returnFormUrl !== "") { ?>
            <button onclick="window.location.href='<?php echo $returnFormUrl; ?>'">
                <?php echo $returnFormLabel; ?>
            </button>
        <?php } ?>
    </div>
</main>
<footer>&copy; 2023-2024 Kelsey Rajher. All Rights Reserved.</footer>
</body>


</html>