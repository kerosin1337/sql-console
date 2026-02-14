<?php
require_once '../src/QueryService.php';
$service = new QueryService();
echo json_encode($service->getById($_GET['id']));
