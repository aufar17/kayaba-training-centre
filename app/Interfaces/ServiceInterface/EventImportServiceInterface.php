<?php

namespace App\Interfaces\ServiceInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface EventImportServiceInterface
{
    public function import(array $rows): void;
}
