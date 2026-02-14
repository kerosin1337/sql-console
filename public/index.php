<?php
require_once '../src/QueryService.php';
$service = new QueryService();
$queries = $service->getAll();
$users = $service->getUsers();

$grouped = [];
foreach ($queries as $q) {
    if ($q['id']) {
        $grouped[$q['user_name']][] = $q;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Консоль SQL запросов</title>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="">

<div class="container py-4">
    <div class="mb-4">
        <h4 class="mb-0">Консоль SQL запросов</h4>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong>Сохранённые запросы</strong>
        </div>
        <div class="card-body p-2">
            <div class="accordion" id="accordion">
                <?php $i = 0;
                foreach ($grouped as $user => $userQueries): ?>
                    <div class="card mb-2 border-0">
                        <div class="card-header bg-light p-2" data-bs-toggle="collapse"
                             data-bs-target="#collapse<?= $i ?>" aria-expanded="false"
                             aria-controls="collapse<?= $i ?>">
                            <div class="d-flex justify-content-between">
                                    <span class="small">
                                        <strong><?= htmlspecialchars($user) ?></strong>
                                    </span>
                                <span class="badge bg-info small">
                                        <?= count($userQueries) ?>
                                    </span>
                            </div>
                        </div>
                        <div id="collapse<?= $i ?>" class="collapse" data-bs-parent="#accordion">
                            <div class="list-group list-group-flush">
                                <?php foreach ($userQueries as $q): ?>
                                    <div class="list-group-item py-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="small fw-bold">
                                                    <?= htmlspecialchars($q['title']) ?>
                                                </div>
                                                <div class="text-muted small">
                                                    <?= htmlspecialchars($q['sql_text']) ?>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-nowrap gap-2">
                                                <button class="btn btn-sm btn-outline-info execute"
                                                        data-id="<?= $q['id'] ?>">
                                                    ▶️
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning edit"
                                                        data-id="<?= $q['id'] ?>">
                                                    ✏️
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete"
                                                        data-id="<?= $q['id'] ?>">
                                                    ❌
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php $i++; endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ===== Результат выполнения ===== -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <strong>Результат выполнения</strong>
        </div>
        <div class="card-body p-2">
            <div id="result" class="small text-muted">
                Выберите и выполните запрос.
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <strong>Добавить новый запрос</strong>
        </div>
        <div class="card-body">
            <form id="queryForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="user_id" class="form-label small">Пользователь</label>
                        <select id="user_id" name="user_id" class="form-select form-select-sm">
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>">
                                    <?= htmlspecialchars($u['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label for="title" class="form-label small">Название запроса</label>
                        <input id="title" type="text" name="title" class="form-control form-control-sm" required>
                    </div>
                </div>
                <div class="mt-3">
                    <label for="sql_text" class="form-label small">SQL текст</label>
                    <textarea id="sql_text" name="sql_text" rows="4" class="form-control form-control-sm"
                              required></textarea>
                </div>
                <button class="btn btn-sm btn-primary mt-3">
                    Сохранить
                </button>
            </form>
        </div>
    </div>
</div>

<script src="assets/js/jquery-4.0.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/index.js"></script>

</body>
</html>
