<?php

namespace App\Services;

use App\Interfaces\RepositoryInterface\LocationRepositoryInterface;
use App\Interfaces\ServiceInterface\LocationServiceInterface;
use App\Models\Location;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class LocationService implements LocationServiceInterface
{
    protected LocationRepositoryInterface $repository;

    public function __construct(LocationRepositoryInterface $repository)
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
            $location = Location::create([
                'code' => $data['code'],
                'name' => $data['name'],
            ]);

            DB::commit();
            return $location;
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
            $location = Location::findOrFail($id);
            $location->update($data);

            DB::commit();
            return $location;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $location = Location::findOrFail($id);
            $deleted = $location->delete();

            DB::commit();
            return $deleted;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
