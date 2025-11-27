<div>
    @if (session('success'))
    <div class="alert alert-success text-white fw-bold">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger text-white fw-bold">{{ session('error') }}</div>
    @endif
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-header border-0 rounded-top-4"
            style="background: linear-gradient(135deg, #d9e9ff, #b9d7ff, #9bc5ff);">
            <h5 class="text-uppercase text-black fw-bolder mb-0" style="letter-spacing: 2px;">
                > {{ $training->name }}
            </h5>
        </div>

        <div class="card-body p-4">
            <div class="row g-4 align-items-start">
                <div class="col-md-9">
                    <table class="table table-borderless mb-0 small align-middle">
                        <tr>
                            <td class="text-bold w-20">Code</td>
                            <td class="fw-semibold">{{ $training->code ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="text-bold w-20">Description</td>
                            <td class="fw-semibold text-wrap">{{ $training->desc ?? '-' }}</td>
                        </tr>

                        <tr>
                            <td class="text-bold w-20">Purpose</td>
                            <td class="fw-semibold text-wrap">{{ $training->purpose ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-bold w-20">Matrix</td>
                            <td class="fw-semibold text-wrap">
                                @if ($training->matrix && $training->matrix->count())
                                {{ $training->matrix->pluck('dept')->implode(', ') }}
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-bold w-20">Golongan</td>
                            <td class="fw-semibold text-wrap">{{ $trainings->golongan ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($user->dept == 'HRD')
    <form wire:submit.prevent=" save">
        <div class="mb-4">
            <span class="fw-bold text-uppercase">Upload Training Content</span>

            <div class="border border-2 border-dashed rounded-3 text-center p-3 bg-light position-relative">
                @if (!$pdf_file)
                <i class="fa-solid fa-file-pdf fs-4 text-info mb-2"></i>
                <div class="fw-semibold">Only PDF files are allowed (max 5MB)</div>
                <small class="text-muted">Click below to select your PDF</small>

                <div class="mt-3">
                    <label for="pdfUpload" class="btn btn-outline-dark btn-sm px-3 py-2 cursor-pointer"
                        style="cursor: pointer; transition: all 0.2s ease-in-out;">
                        <i class="fa-solid fa-upload me-2"></i>Choose PDF
                    </label>
                    <input type="file" id="pdfUpload" class="d-none" accept="application/pdf" wire:model.live="pdf_file"
                        required>
                </div>
                @else
                <div class="d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="fa-solid fa-check-circle text-success fs-4 mb-2"></i>
                    <div class="fw-bolder text-success">File Selected</div>
                    <small class="text-muted">{{ $pdf_file->getClientOriginalName() }}</small>

                    <div class="mt-3">
                        <label for="pdfUpload" class="btn btn-outline-dark btn-sm px-3 py-2 cursor-pointer">
                            <i class="fa-solid fa-repeat me-1"></i>Change File
                        </label>
                        <input type="file" id="pdfUpload" class="d-none" accept="application/pdf"
                            wire:model.defer="pdf_file">
                    </div>
                </div>
                @endif
            </div>

            <small class="d-block mt-2 text-danger">
                <i class="fa-solid fa-circle-info"></i>
                Please wait until the file preview appears after selecting your PDF.
            </small>

        </div>
        <div class="mt-4 text-end">
            <button wire:click="save" class="btn btn-success px-4">
                <i class="bi bi-save me-2"></i>Save
            </button>
        </div>
    </form>
    @endif

    <hr class="my-5">

    <div>
        <h5 class="fw-bold mb-4 text-uppercase">
            <i class="fa-solid fa-folder-open me-2 text-warning"></i>Training Content
        </h5>

        <ul class="list-group list-group-flush">
            @forelse($files as $index => $file)
            <li class="list-group-item d-flex justify-content-between align-items-center shadow rounded-2 mb-3 p-3"
                style="border-left: 4px solid #198754;">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-file-pdf text-danger fs-4 me-2"></i>
                    <span class="fw-bold">{{ $file->file }}</span>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="badge bg-gradient-info p-2 border-0 shadow" data-bs-toggle="modal"
                        data-bs-target="#pdfModal{{ $index }}">
                        <i class="fa-solid fa-eye me-1"></i>
                    </button>
                    <a href="{{ asset('storage/training_content/'.$file->file) }}" download
                        class="badge bg-gradient-success p-2 border-0 shadow cursor-pointer">
                        <i class="fa-solid fa-download me-1"></i>
                    </a>
                    <button type="button" class="badge bg-gradient-danger p-2 border-0 shadow" data-bs-toggle="modal"
                        data-bs-target="#deleteFile{{ $index }}">
                        <i class="fa-solid fa-trash me-1"></i>
                    </button>
                </div>
            </li>


            <div class="modal fade" id="pdfModal{{ $index }}" tabindex="-1" aria-labelledby="pdfModalLabel{{ $index }}"
                aria-hidden="true">
                <div class="modal-dialog modal-fullscreen">
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title" id="pdfModalLabel{{ $index }}">{{
                                $file->file }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <iframe src="{{ asset('storage/training_content/'.$file->file) }}"
                                style="width: 100%;height: 100%"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteFile{{ $index }}" tabindex="-1"
                aria-labelledby="deleteFileLabel{{ $index }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <div class="modal-header bg-danger text-white rounded-top-3">
                            <h5 class="modal-title fw-bold text-white" id="deleteFileLabel{{ $index }}">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i>Delete
                                File
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body text-center py-4">
                            <i class="fa-solid fa-file-pdf text-danger fs-1 mb-3"></i>
                            <p class="fw-semibold mb-1">Are you sure you want to delete this
                                file?</p>
                            <p class="text-muted small fst-italic">{{ $file->file }}</p>
                        </div>

                        <div class="modal-footer border-0 justify-content-center pb-4">
                            <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">
                                <i class="fa-solid fa-xmark me-1"></i>Cancel
                            </button>
                            <button type="button" class="btn btn-danger px-3" wire:click="delete({{ $file->id }})"
                                data-bs-dismiss="modal">
                                <i class="fa-solid fa-trash-can me-1"></i>Yes, Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-muted text-center fst-italic">No files uploaded yet.</div>
            @endforelse

        </ul>
    </div>



</div>