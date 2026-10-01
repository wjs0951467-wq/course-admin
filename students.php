<?php
session_start();
require_once __DIR__ . '/config/db.php';

$pageTitle = 'Students';
$error = '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $major = trim($_POST['major'] ?? '');
    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $error = '페이지를 새로고침한 후 다시 등록해주세요.';
    } elseif ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = '이름과 올바른 이메일을 입력해주세요.';
    } else {
        try {
            $sql = "
                INSERT INTO students (name, email, major)
                VALUES (:name, :email, :major)
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'major' => $major,
            ]);

            $_SESSION['student_added'] = true;
            header('Location: students.php', true, 303);
            exit;
        } catch (PDOException $e) {
            $error = $e->getCode() === '23000'
                ? '이미 등록된 이메일입니다.'
                : '등록에 실패했습니다. 입력값을 확인해주세요.';
        }
    }
}

$studentAdded = $_SESSION['student_added'] ?? false;
unset($_SESSION['student_added']);

$keyword = trim($_GET['keyword'] ?? '');

$sql = "
    SELECT student_id, name, email, major, created_at
    FROM students
";

$params = [];

if ($keyword !== '') {
    $sql .= " WHERE name LIKE :name_keyword OR email LIKE :email_keyword";
    $params = [
        'name_keyword' => '%' . $keyword . '%',
        'email_keyword' => '%' . $keyword . '%',
    ];
}

$sql .= " ORDER BY student_id DESC LIMIT 20";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$students = $stmt->fetchAll();

require __DIR__ . '/header.php';
?>

<h1 class="page-title">학생 관리</h1>

<?php if ($studentAdded): ?>
    <div id="studentAdded" class="alert alert-success">
        Student added.
    </div>
<?php endif; ?>

<?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<div class="content-card mb-4">
    <h2 class="h5 mb-3">학생 등록</h2>

    <form method="post" action="students.php" class="row g-3">
        <input type="hidden" name="csrf_token"
               value="<?= e($_SESSION['csrf_token']) ?>">

        <div class="col-md-4">
            <label for="name" class="form-label">이름</label>
            <input id="name" name="name" class="form-control"
                   maxlength="100" required>
        </div>

        <div class="col-md-4">
            <label for="email" class="form-label">이메일</label>
            <input id="email" type="email" name="email"
                   class="form-control" maxlength="200" required>
        </div>

        <div class="col-md-4">
            <label for="major" class="form-label">전공</label>
            <input id="major" name="major" class="form-control"
                   maxlength="100">
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">학생 등록</button>
        </div>
    </form>
</div>

<div class="content-card">
    <form method="get" action="students.php" class="d-flex gap-2 mb-3">
        <input id="keyword" name="keyword" class="form-control"
               placeholder="이름 또는 이메일 검색"
               value="<?= e($keyword) ?>">

        <button id="searchButton" type="submit"
                class="btn btn-secondary text-nowrap">검색</button>

        <a href="students.php" class="btn btn-outline-secondary text-nowrap">
            초기화
        </a>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-secondary">
            조회 결과 <?= count($students) ?>명 · 최대 50명 표시
        </span>
        <button id="confirmButton" type="button"
                class="btn btn-outline-secondary btn-sm">
            확인창 연습
        </button>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>이름</th>
                    <th>이메일 주소</th>
                    <th>전공</th>
                    <th>등록일</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= e($student['student_id']) ?></td>
                        <td><?= e($student['name']) ?></td>
                        <td><?= e($student['email']) ?></td>
                        <td><?= e($student['major']) ?></td>
                        <td><?= e($student['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (count($students) === 0): ?>
                    <tr>
                        <td colspan="5" class="text-center">
                            검색 결과가 없습니다.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>