<div>
    <style>
        .custom-input-group {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            transition: box-shadow 0.2s ease;
        }

        .custom-input-group .form-control,
        .custom-input-group .input-group-text {
            border: none !important;
            box-shadow: none !important;
        }

        .custom-input-group .form-control:focus {
            outline: none !important;
            box-shadow: none !important;
            border: none !important;
        }

        .input-group-text {
            background-color: transparent !important;
        }
    </style>

    <div class="container-fluid">
        <div class="row g-4 justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                <span class="fw-bold text-uppercase small text-muted">Search Employee</span>
                <div class="input-group custom-input-group py-1 mt-2">
                    <span class="input-group-text ps-3">
                        <i class="fa-solid fa-user text-secondary fs-6 me-2"></i>
                    </span>
                    <input type="text" class="form-control bg-transparent" placeholder="Search by NPK or Name..."
                        wire:model.live="searchEmployee">
                </div>
            </div>

            <div class="col-md-auto d-none d-md-flex justify-content-center px-2">
                <div class="border-start" style="height: 90px;"></div>
            </div>

            <div class="col-12 col-md-6 col-lg-5">
                <span class="fw-bold text-uppercase small text-muted">Search Training</span>
                <div class="input-group custom-input-group py-1 mt-2">
                    <span class="input-group-text ps-3">
                        <i class="fa-solid fa-chalkboard-user text-secondary fs-6 me-2"></i>
                    </span>
                    <input type="text" class="form-control bg-transparent"
                        placeholder="Search by Training Name or Event..." wire:model.live="searchTraining">
                </div>
            </div>
        </div>

        <hr class="my-4">
        <div class="row mt-4 g-3">
            @if(empty($searchEmployee) && empty($searchTraining))
            <div class="col-12 text-center text-muted my-5">
                <span class="fw-bold">Start typing to search for Employee or Training...</span>
            </div>
            @else
            @if(!empty($searchEmployee))
            @if($employees->isEmpty())
            <div class="col-12 text-center text-muted my-3">
                <span class="fw-bold">No Employee results found</span>
            </div>
            @else
            <div class="col-12">
                <h6 class="fw-bold mb-3">Employee Results</h6>
                <div class="row g-3">
                    @foreach($employees as $emp)
                    <div class="col-12 col-sm-12 col-lg-3">
                        <div class="card shadow-sm border-0 hover-shadow">
                            <div class="card-body d-flex align-items-center gap-3">
                                <i class="fa-solid fa-user text-info fs-4 me-3"></i>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-0">{{ $emp->npk }} - {{ $emp->full_name }}</h6>
                                    <a href="{{ route('participant-history',$emp->npk) }}"
                                        class="badge bg-gradient-info border-0 mt-1 align-self-start text-uppercase">
                                        <i class="fa-solid fa-eye me-1"></i>
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif

            @if(!empty($searchTraining))
            @if($events->isEmpty())
            <div class="col-12 text-center text-muted my-3">
                <span class="fw-bold">No Training results found</span>
            </div>
            @else
            <div class="col-12 mt-4">
                <h6 class="fw-bold mb-3">Training Results</h6>
                <div class="row g-3">
                    @foreach($events as $event)
                    <div class="col-12 col-sm-12 col-lg-4">
                        <div class="card shadow-sm border-0 hover-shadow">
                            <div class="card-body d-flex align-items-center gap-3">
                                <i class="fa-solid fa-chalkboard-user text-success fs-4 me-3"></i>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-0">{{ $event->trainings->code }} - {{ $event->trainings->name }}</h6>

                                    <a href="{{ route('event-participant', $event->id) }}"
                                        class="badge bg-gradient-info border-0 mt-1 align-self-start text-uppercase">
                                        <i class="fa-solid fa-eye"></i> View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif
            @endif
        </div>

    </div>
</div>