<?php
require_once '../src/QueryService.php';
$service = new QueryService();
$service->save($_POST);
