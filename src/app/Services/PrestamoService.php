<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\MaterialNoDisponibleException;
use App\Repos\MaterialRepo;

class PrestamoService
{
    public function __construct(private MaterialRepo $repo) {}

    /**
     * Intenta prestar un material a un usuario.
     *
     * @throws MaterialNoDisponibleException si el material ya está prestado
     * @throws \App\Exceptions\MaterialNoEncontradoException si el id no existe
     */
    public function prestar(int $materialId, int $usuarioId): void
    {
        $resultado = 'fallido';
        try {
            $material = $this->repo->findById($materialId);

            if (!$material->prestar()) {
                throw new MaterialNoDisponibleException($material->getTitulo());
            }

            $this->repo->setDisponible($materialId, false);
            $this->repo->registrarPrestamo($materialId, $usuarioId);
            $resultado = 'ok';
        } finally {
            error_log("Intento de préstamo: material {$materialId}, usuario {$usuarioId}, resultado {$resultado}");
        }
    }

    public function devolver(int $materialId): void
    {
        $material = $this->repo->findById($materialId);   // lanza si no existe
        if ($material->isDisponible()) {
            return;                                        // ya estaba devuelto
        }
        $material->devolver();
        $this->repo->setDisponible($materialId, true);
        $material->log("Devolución de '{$material->getTitulo()}'");
    }
}
