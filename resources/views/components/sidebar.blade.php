@props([
'user'
]);

<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="https://demos.creative-tim.com/soft-ui-dashboard/pages/dashboard.html"
            target="_blank">
            <img src="{{ url('https://upload.wikimedia.org/wikipedia/commons/thumb/7/7b/KYB_Corporation_company_logo.svg/2560px-KYB_Corporation_company_logo.svg.png') }}"
                class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Kayaba Indonesia</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100 h-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <x-navlink href="{{ route('index') }}" :active="request()->is('/') " icon="fa-house">Dashboard</x-navlink>
            @if($user->dept == 'HRD')
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Training Data</h6>
            </li>
            <x-navlink href="{{ route('training') }}" :active="request()->is(['training','training-content/*']) "
                icon="fa-list">Training List
            </x-navlink>
            <x-navlink href="{{ route('location') }}" :active="request()->is('location') " icon="fa-location-dot">
                Training
                Location
            </x-navlink>
            <x-navlink href="{{ route('organizer') }}" :active="request()->is('organizer') " icon="fa-people-group">
                Organizer
            </x-navlink>
            <x-navlink href="{{ route('trainer') }}" :active="request()->is('trainer') " icon="fa-person">
                Trainer
            </x-navlink>
            @endif

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Main Event</h6>
            </li>
            <x-navlink href="{{ route('event') }}" :active="request()->is(['event','event-participant/*']) "
                icon="fa-calendar-days">Event
            </x-navlink>
        </ul>
    </div>
</aside>