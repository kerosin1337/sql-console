<?php
require_once '../src/QueryService.php';
$service = new QueryService();
$service->delete($_POST['id']);
