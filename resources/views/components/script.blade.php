<script src="{{ asset('js/core/popper.min.js') }}"></script>
<script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('js/plugins/chartjs.min.js') }}"></script>
<script src="{{ asset('js/curve-chart.js') }}"></script>
<script src="{{asset('js/soft-ui-dashboard.min.js?v=1.0.3') }}"></script>
<script src="{{ asset('js/data-table.js') }}"></script>
<script src="{{ asset('js/jqery.dataTables.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/dataTables-2.1.8.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('js/datatables.js') }}"></script>
<script src="{{ asset('js/chart.js') }}"></script>
<script src="{{ asset('js/chartjs-plugin-datalabels.js') }}"></script>
<script src="{{ asset('js/hydrant-donut-chart.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script>

@livewireScripts

<script>
  var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }