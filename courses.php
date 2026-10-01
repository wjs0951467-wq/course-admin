<?php
require_once __DIR__ . '/config/db.php';

$pageTitle = 'Courses';

$sql = "SELECT c.course_id, c.title, c.category, c.capacity,
               i.name AS instructor_name
        FROM courses c
        JOIN instructors i ON c.instructor_id = i.instructor_id
        ORDER BY c.course_id DESC
        LIMIT 200";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll();

require __DIR__ . '/header.php';
?>

<h1 class="page-title">Courses</h1>

<div class="content-card">
    <h2 class="h5 mb-3">강좌 목록</h2>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>강좌명</th>
                    <th>강사명</th>
                    <th>카테고리</th>
                    <th>정원</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= e($course['course_id']) ?></td>
                        <td><?= e($course['title']) ?></td>
                        <td><?= e($course['instructor_name']) ?></td>
                        <td><?= e($course['category']) ?></td>
                        <td><?= e($course['capacity']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>