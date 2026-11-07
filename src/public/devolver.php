<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Includes/exceptions.php';

use App\Exceptions\MaterialNoEncontradoException;
use App\Repos\MaterialRepo;
use App\Services\PrestamoService;

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit;
}

$id = (int)($_POST['material_id'] ?? 0);

try {
    (new PrestamoService(new MaterialRepo()))->devolver($id);
} catch (MaterialNoEncontradoException $e) {
    http_response_code(404);
    exit('Material no encontrado');
}

header('Location: /material.php?id=' . $id);
exit;
