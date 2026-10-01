<?php
require_once __DIR__ . '/config/db.php';

$pageTitle = 'Enrollments';

$sql = "SELECT e.enrollment_id, s.name AS student_name,
               c.title AS course_title, i.name AS instructor_name,
               e.status, e.enrolled_at
        FROM enrollments e
        JOIN students s ON e.student_id = s.student_id
        JOIN courses c ON e.course_id = c.course_id
        JOIN instructors i ON c.instructor_id = i.instructor_id
        WHERE e.status = 'COMPLETED'
        ORDER BY e.enrolled_at DESC, e.enrollment_id DESC
        LIMIT 100";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$enrollments = $stmt->fetchAll();

require __DIR__ . '/header.php';
?>

<h1 class="page-title">Enrollments</h1>

<div class="content-card">
    <h2 class="h5 mb-3">수강신청 목록</h2>
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
                <?php foreach ($enrollments as $enrollment): ?>
                    <tr>
                        <td><?= e($enrollment['enrollment_id']) ?></td>
                        <td><?= e($enrollment['student_name']) ?></td>
                        <td><?= e($enrollment['course_title']) ?></td>
                        <td><?= e($enrollment['instructor_name']) ?></td>
                        <td><?= e($enrollment['status']) ?></td>
                        <td><?= e($enrollment['enrolled_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>