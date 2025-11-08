<?php

namespace App\Services;

use App\Models\Training;
use App\Interfaces\RepositoryInterface\TrainingRepositoryInterface;
use App\Interfaces\ServiceInterface\TrainingServiceInterface;
use App\Models\TrainingTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

    public function getContent(int $id)
    {
        return TrainingTransaction::where('training_id', $id)->get();
    }

    public function contentUpload(int $id, $file, string $fileName)
    {
        DB::beginTransaction();

        try {

            $path = $file->storeAs('training_content', $fileName, 'public');
            $create = TrainingTransaction::create([
                'training_id' => $id,
                'file' => $fileName
            ]);

            DB::commit();
            return $create;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function contentDelete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $content = TrainingTransaction::findOrFail($id);

            if (Storage::disk('public')->exists('training_content/' . $content->file)) {
                Storage::disk('public')->delete('training_content/' . $content->file);
            }

            $content->delete();

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            return false;
        }
    }
}
