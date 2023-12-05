
function handleBoxClick(boxNumber) {
    switch (boxNumber) {
        case 1:
            window.location.href = 'u_residency_request.php';
            break;
        case 2:
            window.location.href = 'u_clearance_request.php';
            break;
        case 3:
            window.location.href = 'ub_clearance_request.php';
            break;
        case 4:
            window.location.href = 'ul_income_request.php';
            break;
        default:
 
            break;
    }
}