<div>
    <style>
        .hero-banner {
            background: url("{{ asset('img/background.png') }}") center/cover no-repeat;
            border-radius: 20px;
            height: 240px;
            position: relative;
            display: flex;
            align-items: center;
            color: white;
            overflow: hidden;
        }

        /* .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.52) 0%,
                    rgba(236, 121, 121, 0.38) 45%,
                    rgba(200, 0, 0, 0.23) 100%);
        } */

        .kpi-card {
            padding: 26px;
            border-radius: 18px;
            transition: .25s;
            cursor: pointer;
            color: #fff;
        }

        .kpi-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .25);
        }

        .kpi-card i {
            text-shadow: 0 0 6px rgba(255, 255, 255, 0.35);
            transition: .25s;
        }

        .kpi-card:hover i {
            transform: scale(1.12);
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.55);
        }


        .menu-card {
            border-radius: 20px;
            padding: 38px 20px;
            text-align: center;
            background: #fff;
            transition: .25s;
            cursor: pointer;
        }

        .menu-card:hover {
            transform: translateY(-8px) scale(1.04);
            box-shadow: 0 15px 35px rgba(0, 0, 0, .15);
        }

        .menu-card i {
            transition: .3s;
        }

        .menu-card:hover i {
            transform: scale(1.13);
        }

        .overview {
            background: linear-gradient(135deg, #004AAD, #0B82FF);
            border-radius: 20px;
            padding: 32px;
            color: #fff;
            transition: .35s ease-in-out;
            cursor: pointer;
        }

        .overview i {
            transition: .35s ease-in-out;
        }

        .overview:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.18) !important;
        }

        .overview:hover i {
            transform: scale(1.12);
            opacity: 1 !important;
        }
    </style>

    <div class="container-fluid">

        <h2 class="fw-bolder mb-1 text-uppercase" style="letter-spacing: 2px">Kayaba Training Centre</h2>

        <div class="hero-banner shadow-lg mb-5">
            <div class="hero-overlay"></div>
        </div>
        <div class="row g-3 mb-5">
            @foreach ($mappings as $item)
            <div class="col-12 col-md-6 col-lg-3">
                <div class="kpi-card shadow" style="background: {{ $item['gradient'] }};">

                    <i class="fa-solid {{ $item['icon'] }} fa-2x mb-2"></i>
                    <p class="small opacity-75 m-0 text-bolder">{{ $item['title'] }}</p>
                    <h2 class="fw-bold text-light m-0">{{ $item['value'] }}</h2>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row g-3 mb-5">
            <div class="col-lg-6">
                <div class="overview shadow d-flex justify-content-between align-items-center"
                    style="background: linear-gradient(130deg,#FF6B6B,#FFD6A5); border-radius: 14px; padding: 22px;">
                    <div>
                        <h5 class="fw-bold mb-1 text-white"><i class="fa-solid fa-bell me-2"></i>Upcoming Training</h5>
                        <p class="small opacity-75 mb-2">{{ $upcoming->trainings->name ?? 'No Upcoming Training' }}</p>
                        @if ($upcoming)
                        <span class="badge bg-light text-dark px-3 py-2">{{ $upcoming->startDateFormat() }} - {{
                            $upcoming->endDateFormat() }}</span>
                        @else
                        <span class="badge bg-light text-dark px-3 py-2">Not Available</span>
                        @endif
                    </div>
                    <i class="fa-solid fa-bullhorn fa-3x opacity-75"></i>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="overview shadow d-flex justify-content-between align-items-center"
                    style="background: linear-gradient(130deg,#007BFF,#00D4FF); border-radius: 14px; padding: 22px;">
                    <div>
                        <h5 class="fw-bold mb-1 text-white"><i class="fa-solid fa-play me-2"></i>Ongoing Training</h5>
                        <p class="small opacity-75 mb-2">{{ $now->trainings->name ?? 'No Ongoing Training' }}</p>
                        @if ($now)
                        <span class="badge bg-light text-dark px-3 py-2">{{ $now->startDateFormat() }} - {{
                            $now->endDateFormat() }}</span>
                        @else
                        <span class="badge bg-light text-dark px-3 py-2">Not Available</span>
                        @endif
                    </div>
                    <i class="fa-solid fa-hourglass-half fa-3x opacity-75"></i>
                </div>
            </div>

        </div>
    </div>

</div>