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

<body class="g-sidenav-show  bg-gray-100">
    <x-sidebar></x-sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        <x-navbar>Dashboard</x-navbar>
        <div class="container-fluid py-4">
            <x-kpi></x-kpi>
            <div class="row mt-4">
                <div class="col-lg-7 mb-lg-0 mb-4">
                    <x-main-card></x-main-card>
                </div>
                <div class="col-lg-5">
                    <x-second-card></x-second-card>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-5 mb-lg-0 mb-4">
                    <x-bar-graph></x-bar-graph>
                </div>
                <div class="col-lg-7">
                    <x-line-graph></x-line-graph>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
                    <x-progress-table></x-progress-table>
                </div>
                <div class="col-lg-4 col-md-6">
                    <x-progress-overview></x-progress-overview>
                </div>
            </div>
            <x-footer></x-footer>
        </div>
    </main>
    <x-script></x-script>
</body>

</html>