// Function for confirm role delete feature
function confirmDeleteRole(jobName, deleteUrl) {
    var confirmDelete = confirm("Are you sure you want to delete role '" + jobName + "'?");
    if (confirmDelete) {
        window.location.href = deleteUrl;
    }
}
// Function for confirm cost center delete feature
function confirmDeleteCostCenter(costCenterName, deleteUrl) {
    var confirmDelete = confirm("Are you sure you want to delete cost center '" + costCenterName + "'?");
    if (confirmDelete) {
        window.location.href = deleteUrl;
    }
}

