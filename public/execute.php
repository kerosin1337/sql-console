<?php
require_once '../src/QueryService.php';
$service = new QueryService();

$query = $service->getById($_POST['id']);

try {
    $result = $service->executeQuery($query['sql_text']);

    if (!$result) {
        echo "<div class='alert alert-warning small'>Нет данных</div>";
        exit;
    }

    echo "<table class='table table-sm table-bordered'>";
    echo "<tr>";
    foreach (array_keys($result[0]) as $col) {
        echo "<th>$col</th>";
    }
    echo "</tr>";

    foreach ($result as $row) {
        echo "<tr>";
        foreach ($row as $cell) {
            echo "<td>" . htmlspecialchars($cell) . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";

} catch (Exception $e) {
    echo "<div class='alert alert-danger small'>Ошибка: " . $e->getMessage() . "</div>";
}
