<?php

namespace App\Http\Controllers;

use App\Exports\PresenceExport;
use App\Imports\EventImport;
use App\Interfaces\RepositoryInterface\EventRepositoryInterface;
use App\Interfaces\ServiceInterface\EventImportServiceInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EventController extends Controller
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

    public function presenceExport($id, EventRepositoryInterface $eventRepo)
    {
        return Excel::download(
            new PresenceExport($eventRepo, $id),
            'Absensi.xlsx'
        );
    }
}
