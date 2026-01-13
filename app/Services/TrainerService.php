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
                'npk' => $data['npk'],
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

    public function findByName(string $name): ?Trainer
    {
        return $this->repository->findByName($name);
    }

    public function generateNextCode(): string
    {
        $lastTrainer = $this->repository->getLastTrainer();
        $lastCode = $lastTrainer?->code ?? 'TA0000';

        preg_match('/TA(\d+)/', $lastCode, $matches);
        $nextNumber = isset($matches[1]) ? ((int)$matches[1] + 1) : 1;

        return 'TA' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function findOrCreateByName(string $name): Trainer
    {
        return DB::transaction(function () use ($name) {
            $name = trim($name);

            $trainer = $this->findByName($name);
            if ($trainer) {
                return $trainer;
            }

            return Trainer::create([
                'code' => $this->generateNextCode(),
                'name' => $name,
            ]);
        });
    }
}
