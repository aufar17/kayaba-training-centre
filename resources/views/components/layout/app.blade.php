<!--
=========================================================
* Soft UI Dashboard - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)

* Coded by Creative Tim

=========================================================
    
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<x-head></x-head>

@props([
'user',
'nav'
])

<body class="g-sidenav-show bg-gray-100 d-flex flex-column min-vh-100">
    <x-sidebar></x-sidebar>
    <main class="main-content position-relative border-radius-lg flex-grow-1 d-flex flex-column">
        <x-navbar :user="$user">
            @slot('title')
            {{ $nav }}
            @endslot
            @slot('role')
            {{ $user->full_name }}
            @endslot
        </x-navbar>
        <div class="container-fluid py-4 flex-grow-1">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row mt-4">
                <div class="col-12">
                    {{ $slot }}
                </div>
            </div>
        </div>
        <x-footer></x-footer>
    </main>
    <x-script></x-script>
    <script src="{{asset('js/datatables.js')}}"></script>
</body>


</html>