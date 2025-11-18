<?php

namespace App\Interfaces\RepositoryInterface;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface EventRepositoryInterface
{
    public function getModel(): Builder;

    public function find(int $id);

    public function participantModel(): Builder;
    public function filterByMatrixDepartment(string $dept): Builder;
}
