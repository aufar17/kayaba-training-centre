<div class="container-fluid">
    @if ($notifications->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="fa-regular fa-bell-slash fa-3x mb-3"></i>
        <h6 class="fw-semibold">No Notifications Yet</h6>
    </div>
    @else
    <div class="d-flex flex-column gap-2">
        @foreach ($notifications as $notification)
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body d-flex gap-3 py-3 position-relative">
                <div class="flex-shrink-0 bg-{{ $notification['color'] }} text-white rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 42px; height: 42px;">
                    <i class="{{ $notification['icon'] }}"></i>
                </div>
                <div class="flex-grow-1 position-relative">
                    <small class="position-absolute top-0 end-0 text-secondary small fst-italic">
                        {{ $notification['time'] }}
                    </small>

                    <h6 class="fw-semibold mb-1">{{ $notification['title'] }}</h6>

                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-muted small mb-0">{!! $notification['description'] !!}</p>
                        <a href="{{ route($notification['url'],$notification['event_id']) }}"
                            class="badge bg-info border-0 shadow-lg ms-3">
                            <i class="fa-solid fa-arrow-right me-1"></i> View
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>