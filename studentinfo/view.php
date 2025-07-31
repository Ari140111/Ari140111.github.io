<?php
include 'config/db.php';
include 'includes/header.php';

include 'config/courses.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$search = '';
$filter_course = '';
$where = [];
$params = [];

if (isset($_GET['search']) && strlen(trim($_GET['search'])) > 0) {
    $search = trim($_GET['search']);
    $where[] = "name LIKE :search";
    $params[':search'] = '%' . $search . '%';
}

if (isset($_GET['filter_course']) && $_GET['filter_course'] !== '' && in_array($_GET['filter_course'], $courses)) {
    $filter_course = $_GET['filter_course'];
    $where[] = "course = :course";
    $params[':course'] = $filter_course;
}

$where_sql = '';
if (!empty($where)) {
    $where_sql = 'WHERE ' . implode(' AND ', $where);
}

// --- Pagination Logic ---
$records_per_page = 5; // Number of records to display per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $records_per_page;

// Get total number of records for pagination
$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM students $where_sql");
$total_stmt->execute($params);
$total_records = $total_stmt->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Fetch records for the current page, ordering by name
$sql = "SELECT * FROM students $where_sql ORDER BY name ASC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);

// Bind search/filter parameters
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

// Bind pagination parameters as integers
$stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$students = $stmt->fetchAll();
?>

<div class="container py-4 content-container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow" style="background-color: #fff7ee;">
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #d2b48c;">
                    <h4 class="mb-0 text-white">Student Records</h4>
                    <a href="add.php" class="btn btn-primary">Add New</a>
                </div>
                <div class="card-body">
                    <form method="get" class="mb-3">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>">
                            </div>
                            <div class="col-md-4">
                                <select name="filter_course" class="form-select">
                                    <option value="">Filter by course</option>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?= htmlspecialchars($course) ?>" <?= $filter_course === $course ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($course) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2 d-grid gap-2 d-md-flex">
                                <button class="btn btn-primary" type="submit">Search/Filter</button>
                                <?php if ($search || $filter_course): ?>
                                    <a href="view.php" class="btn btn-secondary ms-2">Reset</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                    <th>Date of Birth</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($students)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No students found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?= $student['id'] ?></td>
                                        <td><?= htmlspecialchars($student['name']) ?></td>
                                        <td><?= htmlspecialchars($student['email']) ?></td>
                                        <td><?= htmlspecialchars($student['course']) ?></td>
                                        <td><?= $student['dob'] ?></td>
                                        <td>
                                            <a href="edit.php?id=<?= $student['id'] ?>" class="btn btn-warning me-1" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" title="Delete"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                    data-student-id="<?= $student['id'] ?>" data-student-name="<?= htmlspecialchars($student['name']) ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <?php if ($total_pages > 1): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <?php
                                // Build query string to preserve search/filter on page change
                                $query_params = [];
                                if ($search) $query_params['search'] = $search;
                                if ($filter_course) $query_params['filter_course'] = $filter_course;
                                $query_string = http_build_query($query_params);
                                ?>
                                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>&<?= $query_string ?>">Previous</a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&<?= $query_string ?>"><?= $i ?></a>
                                </li>
                                <?php endfor; ?>
                                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>&<?= $query_string ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this student record? This action cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a id="confirmDeleteBtn" href="#" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const studentId = button.getAttribute('data-student-id');
            const studentName = button.getAttribute('data-student-name');
            
            const modalBody = deleteModal.querySelector('.modal-body');
            modalBody.textContent = `Are you sure you want to delete the record for ${studentName}? This action cannot be undone.`;

            const confirmBtn = deleteModal.querySelector('#confirmDeleteBtn');
            confirmBtn.href = `delete.php?id=${studentId}`;
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>