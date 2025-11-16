<?php

namespace App\Repositories;

use App\Models\Training;
use App\Interfaces\RepositoryInterface\TrainingRepositoryInterface;
use Illuminate\Support\Collection;

class TrainingRepository implements TrainingRepositoryInterface
{
    public function getAll(): Collection
    {
        return Training::get();
    }

    public function find(int $id)
    {
        return Training::with(['matrix'])->findOrFail($id);
    }
}
