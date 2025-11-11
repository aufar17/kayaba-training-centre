<div>

    <style>
        .card {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }
    </style>

    <div class="container-fluid">
        <div class="row g-3">
            @foreach ($mappings as $mapping)
            <div class="col-12 col-md-12 col-lg-6">
                <div class="card text-center shadow-sm border-0 card-hover">
                    <div class="card-body">
                        <i class="fa-solid {{ $mapping['icon'] }} fa-2x {{ $mapping['color'] }} mb-3"></i>
                        <h6 class="text-muted mb-1">{{ $mapping['title'] }}</h6>
                        <h4 class="fw-bold mb-0">{{ $mapping['value'] }}</h4>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>