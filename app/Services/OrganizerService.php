<?php

namespace App\Services;

use App\Interfaces\RepositoryInterface\OrganizerRepositoryInterface;
use App\Interfaces\ServiceInterface\OrganizerServiceInterface;
use App\Models\Organizer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrganizerService implements OrganizerServiceInterface
{
    protected OrganizerRepositoryInterface $repository;

    public function __construct(OrganizerRepositoryInterface $repository)
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
            $organizer = Organizer::create([
                'code' => $data['code'],
                'name' => $data['name'],
            ]);

            DB::commit();
            return $organizer;
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
            $organizer = Organizer::findOrFail($id);
            $organizer->update($data);

            DB::commit();
            return $organizer;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $organizer = Organizer::findOrFail($id);
            $deleted = $organizer->delete();

            DB::commit();
            return $deleted;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
