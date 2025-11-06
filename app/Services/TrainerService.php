<?php

namespace App\Services;

use App\Interfaces\RepositoryInterface\TrainerRepositoryInterface;
use App\Interfaces\ServiceInterface\TrainerServiceInterface;
use App\Models\Trainer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TrainerService implements TrainerServiceInterface
{
    protected TrainerRepositoryInterface $repository;

    public function __construct(TrainerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }
    public function getById(int $id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $trainer = Trainer::create([
                'code' => $data['code'],
                'name' => $data['name'],
            ]);

            DB::commit();
            return $trainer;
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e);
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();

        try {
            $trainer = Trainer::findOrFail($id);
            $trainer->update($data);

            DB::commit();
            return $trainer;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $trainer = Trainer::findOrFail($id);
            $deleted = $trainer->delete();

            DB::commit();
            return $deleted;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
