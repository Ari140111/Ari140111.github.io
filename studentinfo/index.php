<?php 
include 'includes/header.php'; 

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<div class="container py-5 content-container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow" style="background-color: #fff7ee;">
                <div class="card-header text-center" style="background-color: #d2b48c;">
                    <h4 class="mb-0 text-white">Welcome to Student Information System</h4>
                </div>
                <div class="card-body text-center">
                    <p class="card-text mb-4">Manage student records with ease.</p>
                    <a href="add.php" class="btn btn-primary me-2">Add New Student</a>
                    <a href="view.php" class="btn btn-secondary">View All Students</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>