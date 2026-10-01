<?php
require_once __DIR__ . '/config/db.php';

$pageTitle = 'Dashboard';

$studentCount = $pdo->query(
    'SELECT COUNT(*) FROM students'
)->fetchColumn();

$instructorCount = $pdo->query(
    'SELECT COUNT(*) FROM instructors'
)->fetchColumn();

$courseCount = $pdo->query(
    'SELECT COUNT(*) FROM courses'
)->fetchColumn();

$enrollmentCount = $pdo->query(
    'SELECT COUNT(*) FROM enrollments'
)->fetchColumn();

$sql = "
    SELECT
        e.enrollment_id,
        s.name AS student_name,
        c.title AS course_title,
        i.name AS instructor_name,
        e.status,
        e.enrolled_at
    FROM enrollments e
    JOIN students s ON e.student_id = s.student_id
    JOIN courses c ON e.course_id = c.course_id
    JOIN instructors i ON c.instructor_id = i.instructor_id
    ORDER BY e.enrolled_at DESC, e.enrollment_id DESC
    LIMIT 10
";

$recentEnrollments = $pdo->query($sql)->fetchAll();

require __DIR__ . '/header.php';
?>

<h1 class="page-title">Dashboard</h1>

<div class="row g-3 mb-4">
    <?php
    $cards = [
        '전체 학생' => $studentCount,
        '전체 강사' => $instructorCount,
        '전체 강좌' => $courseCount,
        '전체 수강신청' => $enrollmentCount,
    ];
    ?>
    <?php foreach ($cards as $label => $count): ?>
        <div class="col-md-6 col-xl-3">
            <div class="dashboard-card">
                <div class="text-secondary"><?= e($label) ?></div>
                <div class="count"><?= number_format((int) $count) ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="content-card">
    <h2 class="h5 mb-3">최근 수강신청 10건</h2>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>학생명</th>
                    <th>강좌명</th>
                    <th>강사명</th>
                    <th>상태</th>
                    <th>신청일</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentEnrollments as $row): ?>
                    <tr>
                        <td><?= e($row['enrollment_id']) ?></td>
                        <td><?= e($row['student_name']) ?></td>
                        <td><?= e($row['course_title']) ?></td>
                        <td><?= e($row['instructor_name']) ?></td>
                        <td><?= e($row['status']) ?></td>
                        <td><?= e($row['enrolled_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>