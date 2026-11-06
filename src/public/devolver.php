<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Includes/auth.php';
requireLogin();
require_once __DIR__ . '/../app/Repos/MaterialRepo.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['material_id'] ?? 0);
$repo = new MaterialRepo();
$material = $repo->findById($id);

if (!$material) {
    http_response_code(404);
    exit('Material no encontrado');
}

if (!$material->isDisponible()) {
    $material->devolver();
    $repo->setDisponible($id, true);
    $material->log("Devolución de '{$material->getTitulo()}' por " . currentUser()['email']);
}

header('Location: material.php?id=' . $id);
exit;
