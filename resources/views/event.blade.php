<x-layout.app :user="$user" nav="Event">
    @if ($user->dept == 'HRD')
    <livewire:events />
    @else
    <livewire:event-user :user="$user" />
    @endif

</x-layout.app>


<script>
    $(document).ready(function () {
        $('#event').DataTable({
            responsive: true,
            autoWidth: false,
            paging: true,           
            info: true,          
            searching: true,
            ordering: true,
            lengthChange: true,  
            pageLength: 25,      
            order: [[0, "asc"]],
        });
    });
    $(document).ready(function () {
        $('#event-user').DataTable({
            responsive: true,
            autoWidth: false,
            paging: true,           
            info: true,          
            searching: true,
            ordering: true,
            lengthChange: true,  
            pageLength: 25,      
            order: [[0, "asc"]],
        });
    });
</script>