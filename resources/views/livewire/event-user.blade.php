<div>
    <style>
        .card:hover {
            transform: scale(1.01);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .nav-tabs .nav-link {
            color: #FF8F8F;
            background-color: #f8f9fa;
            border: none;
            border-radius: 8px 8px 0 0;
            margin: 0 3px;
            transition: all 0.2s ease;
        }

        .nav-tabs .nav-link:hover {
            background-color: #e9ecef;
            color: #E62727;
        }

        .nav-tabs .nav-link.active {
            color: white !important;
            background-color: #8C1007 !important;
            font-weight: 600;
            border: none;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }
    </style>

    <div class="text-center mb-3">
        <h2 class="fw-bold text-uppercase mb-2 text-black" style="letter-spacing: 10px">
            <i class="fa-solid fa-calendar-days me-2"></i>Training & Events
        </h2>
        <p class="text-muted mb-0">Stay updated with ongoing, upcoming, and completed events</p>
    </div>

    <ul class="nav nav-tabs nav-fill justify-content-center mb-4" id="eventTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-semibold" id="now-tab" data-bs-toggle="tab" data-bs-target="#now"
                type="button" role="tab" aria-controls="now" aria-selected="true">
                <i class="fa-solid fa-play me-1"></i>Now Event
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming"
                type="button" role="tab" aria-controls="upcoming" aria-selected="false">
                <i class="fa-solid fa-clock me-1"></i>Upcoming
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button"
                role="tab" aria-controls="past" aria-selected="false">
                <i class="fa-solid fa-check me-1"></i>Past
            </button>
        </li>
    </ul>
    <div class="tab-content" id="eventTabsContent">

        <div class="tab-pane fade show active" id="now" role="tabpanel" aria-labelledby="now-tab">
            <div class="row g-4">
                @forelse ($nowEvent as $now)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-lg h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-gradient-success rounded-pill px-3 py-2">Ongoing</span>
                                <small class="text-black ">
                                    <i class="fa-regular fa-calendar me-1"></i> {{ $now->startDateFormat() }} - {{
                                    $now->endDateFormat() }}
                                </small>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">{{ $now->trainings->name }}</h5>

                            <p class="text-muted small mb-3 text-justify">
                                {{$now->trainings->purpose}}
                            </p>
                            <ul class="list-unstyled small mb-2">
                                <li class="mb-1 text-black fw-bolder">
                                    <i class="fa-solid fa-location-dot me-2 text-success"></i>
                                    <span>{{ $now->locations->name }}</span>
                                </li>
                                <li class="mb-1 text-black fw-bolder">
                                    <i class="fa-regular fa-clock me-2 text-success"></i>
                                    <span>{{ $now->start_time }} - {{ $now->end_time }}</span>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('training-content', $now->id) }}"
                                    class="btn btn-info btn-sm me-2 shadow-lg">
                                    <i class="fa-solid fa-file me-1"></i> View
                                </a>
                                <a href="{{ route('event-participant', $now->id) }}"
                                    class="btn btn-success btn-sm shadow-lg">
                                    <i class="fa-solid fa-eye me-1"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 mt-5">
                    <p class="text-center">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        No events are available at the moment.
                    </p>
                </div>
                @endforelse

            </div>
        </div>

        <div class="tab-pane fade" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
            <div class="row g-4">
                @forelse ($upcomingEvent as $upcoming)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-lg h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-gradient-info rounded-pill px-3 py-2">Upcoming</span>
                                <small class="text-black ">
                                    <i class="fa-regular fa-calendar me-1"></i> {{ $upcoming->startDateFormat() }} - {{
                                    $upcoming->endDateFormat() }}
                                </small>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">{{ $upcoming->trainings->name }}</h5>

                            <p class="text-muted small mb-3 text-justify">
                                {{$upcoming->trainings->purpose}}
                            </p>
                            <ul class="list-unstyled small mb-2">
                                <li class="mb-1 text-black fw-bolder">
                                    <i class="fa-solid fa-location-dot me-2 text-info"></i>
                                    <span>{{ $upcoming->locations->name }}</span>
                                </li>
                                <li class="mb-1 text-black fw-bolder">
                                    <i class="fa-regular fa-clock me-2 text-info"></i>
                                    <span>{{ $upcoming->start_time }} - {{ $upcoming->end_time }}</span>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('training-content', $upcoming->id) }}"
                                    class="btn btn-info btn-sm me-2 shadow-lg">
                                    <i class="fa-solid fa-file me-1"></i> View
                                </a>
                                <a href="{{ route('event-participant', $upcoming->id) }}"
                                    class="btn btn-info btn-sm shadow-lg">
                                    <i class="fa-solid fa-cash-register me-1"></i> Register
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 mt-5">
                    <p class="text-center">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        No events are available at the moment.
                    </p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
            <div class="row g-4">
                @forelse ($pastEvent as $past)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-lg h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-gradient-secondary rounded-pill px-3 py-2">Completed</span>
                                <small class="text-black ">
                                    <i class="fa-regular fa-calendar me-1"></i> {{ $past->startDateFormat() }} - {{
                                    $past->endDateFormat() }}
                                </small>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">{{ $past->trainings->name }}</h5>

                            <p class="text-muted small mb-3 text-justify">
                                {{$past->trainings->purpose}}
                            </p>
                            <ul class="list-unstyled small mb-2">
                                <li class="mb-1 text-black fw-bolder">
                                    <i class="fa-solid fa-location-dot me-2 text-secondary"></i>
                                    <span>{{ $past->locations->name }}</span>
                                </li>
                                <li class="mb-1 text-black fw-bolder">
                                    <i class="fa-regular fa-clock me-2 text-secondary"></i>
                                    <span>{{ $past->start_time }} - {{ $past->end_time }}</span>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('training-content', $past->id) }}"
                                    class="btn btn-info btn-sm me-2 shadow-lg">
                                    <i class="fa-solid fa-file me-1"></i> View
                                </a>
                                <a href="{{ route('event-participant', $past->id) }}"
                                    class="btn btn-secondary btn-sm shadow-lg">
                                    <i class="fa-solid fa-eye me-1"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 mt-5">
                    <p class="text-center">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        No events are available at the moment.
                    </p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>