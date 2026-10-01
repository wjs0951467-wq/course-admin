<?php
function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$menus = [
    'index.php' => 'Dashboard',
    'students.php' => 'Students',
    'instructors.php' => 'Instructors',
    'courses.php' => 'Courses',
    'enrollments.php' => 'Enrollments',
];

$currentPage = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> | Course Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body>
<div class="app-layout">
    <aside class="sidebar">
        <a class="brand" href="index.php">Course Admin</a>
        <nav>
            <?php foreach ($menus as $file => $label): ?>
                <a href="<?= e($file) ?>"
                   class="menu-link <?= $currentPage === $file ? 'active' : '' ?>">
                    <?= e($label) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>
    <div class="main-area">
        <header class="topbar">온라인 강좌 관리 시스템</header>
        <main class="container-fluid p-4">