<?php
include 'config/db.php';
include 'includes/header.php';

include 'config/courses.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$error = '';
$student = [];
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: view.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    header("Location: view.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $dob = $_POST['dob'];

    $checkStmt = $pdo->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
    $checkStmt->execute([$email, $id]);
    
    if ($checkStmt->rowCount() > 0) {
        $error = "This email is already registered to another student!";
    } else {
        try {
            $updateStmt = $pdo->prepare("UPDATE students SET name=?, email=?, course=?, dob=? WHERE id=?");
            $updateStmt->execute([$name, $email, $course, $dob, $id]);
            header("Location: view.php");
            exit();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { 
                $error = "This email is already registered to another student!";
            } else {
                $error = "An error occurred while updating the record.";
            }
        }
    }
}
?>

<div class="container py-4 content-container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow" style="background-color: #fff7ee;">
                <div class="card-header" style="background-color: #d2b48c;">
                    <h4 class="mb-0 text-white">Edit Student</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= htmlspecialchars($student['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($student['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="course" class="form-label">Course</label>
                            <select class="form-select" id="course" name="course" required>
                                <option value="" disabled>Select course</option>
                                <?php foreach ($courses as $course_option): ?>
                                    <option value="<?= htmlspecialchars($course_option) ?>"<?= $student['course'] == $course_option ? ' selected' : '' ?>>
                                        <?= htmlspecialchars($course_option) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" 
                                   value="<?= htmlspecialchars($student['dob']) ?>" required>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Update Student</button>
                            <a href="view.php" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>