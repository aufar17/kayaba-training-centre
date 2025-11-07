<?php

namespace App\Services;

use App\Models\Training;
use App\Interfaces\RepositoryInterface\TrainingRepositoryInterface;
use App\Interfaces\ServiceInterface\TrainingServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TrainingService implements TrainingServiceInterface
{
    protected TrainingRepositoryInterface $repository;

    public function __construct(TrainingRepositoryInterface $repository)
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
            $training = Training::create([
                'code' => $data['code'],
                'name' => $data['name'],
                'desc' => $data['desc'],
                'purpose' => $data['purpose'],
                'day_duration' => $data['day_duration'],
                'time_duration' => $data['time_duration'],
            ]);

            DB::commit();
            return $training;
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
            $training = Training::findOrFail($id);
            $training->update($data);

            DB::commit();
            return $training;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $training = Training::findOrFail($id);
            $deleted = $training->delete();

            DB::commit();
            return $deleted;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
