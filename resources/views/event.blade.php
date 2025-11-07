<x-layout.app :user="$user" nav="Event">
    <livewire:events />
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
            pageLength: 10,      
            order: [[0, "asc"]],
        });
    });
</script>