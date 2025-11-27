<?php

namespace App\Http\Controllers;

use App\Imports\EventImport;
use App\Interfaces\ServiceInterface\EventImportServiceInterface;
use Illuminate\Http\Request;

class EventImportController extends Controller
{
    protected EventImportServiceInterface $eventImportService;

    public function __construct(EventImportServiceInterface $eventImportService)
    {
        $this->eventImportService = $eventImportService;
    }

    public function eventImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:51200',
        ]);

        $file = $request->file('file');

        $rows = EventImport::readAllSheets($file);

        $this->eventImportService->import($rows);

        return back()->with('success', 'Import Data Successfully!');
    }
}
