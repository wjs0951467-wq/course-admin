<?php
require_once __DIR__ . '/config/db.php';

$pageTitle = 'Instructors';

$sql = "SELECT instructor_id, name, email, department, created_at
        FROM instructors
        ORDER BY instructor_id DESC
        LIMIT 50";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$instructors = $stmt->fetchAll();

require __DIR__ . '/header.php';
?>

<h1 class="page-title">Instructors</h1>

<div class="content-card">
    <h2 class="h5 mb-3">강사 목록</h2>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>이름</th>
                    <th>이메일</th>
                    <th>학과</th>
                    <th>등록일</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($instructors as $instructor): ?>
                    <tr>
                        <td><?= e($instructor['instructor_id']) ?></td>
                        <td><?= e($instructor['name']) ?></td>
                        <td><?= e($instructor['email']) ?></td>
                        <td><?= e($instructor['department']) ?></td>
                        <td><?= e($instructor['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>