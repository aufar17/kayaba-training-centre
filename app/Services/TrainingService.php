<?php

namespace App\Services;

use App\Models\Training;
use App\Interfaces\RepositoryInterface\TrainingRepositoryInterface;
use App\Interfaces\ServiceInterface\TrainingServiceInterface;
use App\Models\MatrixTraining;
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
                'golongan' => $data['golongan'],
                'duration' => $data['duration'],
            ]);

            if (!empty($data['departments'])) {
                $matrixData = [];
                foreach ($data['departments'] as $dept) {
                    $matrixData[] = [
                        'training_id' => $training->code,
                        'dept' => $dept,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                MatrixTraining::upsert(
                    $matrixData,
                    ['training_id', 'dept'],
                    ['updated_at']
                );
            }

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

            foreach ($data as $key => $value) {
                if ($key === 'departments') continue;

                if ($value !== null) {
                    $training->$key = $value;
                }
            }
            $training->save();

            if (isset($data['departments']) && is_array($data['departments'])) {
                $selectedDepartments = $data['departments'];

                $existingMatrix = $training->matrix()->pluck('dept')->toArray();

                $toDelete = array_diff($existingMatrix, $selectedDepartments);
                if (!empty($toDelete)) {
                    $training->matrix()->whereIn('dept', $toDelete)->delete();
                }

                $toAdd = array_diff($selectedDepartments, $existingMatrix);
                foreach ($toAdd as $deptCode) {
                    $training->matrix()->create(['dept' => $deptCode]);
                }
            }

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
