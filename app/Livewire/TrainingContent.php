<?php

namespace App\Livewire;

use App\Interfaces\ServiceInterface\TrainingServiceInterface;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\TrainingService;

class TrainingContent extends Component
{
    use WithFileUploads;

    public $id;
    public $pdf_file;

    protected $rules = [
        'pdf_file' => 'nullable|file|mimes:pdf|max:5120',
    ];

    protected TrainingServiceInterface $trainingService;

    public function mount()
    {
        $this->trainingService = app(TrainingServiceInterface::class);
    }

    public function render()
    {
        $data = [
            'training' => $this->getTraining($this->id),
            'files' => $this->getContent($this->id),
        ];
        return view('livewire.training-content', $data);
    }

    public function getTraining($id)
    {
        $this->trainingService = app(TrainingServiceInterface::class);
        return $this->trainingService->getById($id) ?? collect();
    }

    public function getContent($id)
    {
        $this->trainingService = app(TrainingServiceInterface::class);
        return $this->trainingService->getContent($id) ?? collect();
    }

    public function updatedPdfFile()
    {
        $this->validate();
    }

    public function save()
    {
        $this->trainingService = app(TrainingServiceInterface::class);

        $this->validate();

        if ($this->pdf_file) {
            $fileName = $this->pdf_file->getClientOriginalName();

            $create = $this->trainingService->contentUpload(
                (int) $this->id,
                $this->pdf_file,
                $fileName
            );

            if ($create) {
                session()->flash('success', $fileName . ' uploaded successfully!');
                $this->pdf_file = null;
            } else {
                session()->flash('error', 'Failed to upload PDF. Please try again.');
            }
        }
    }

    public function delete($id)
    {
        $this->trainingService = app(TrainingServiceInterface::class);

        $deleted = $this->trainingService->contentDelete((int) $id);

        if ($deleted) {
            session()->flash('success', 'File deleted successfully!');
        } else {
            session()->flash('error', 'Failed to delete file.');
        }
    }
}
