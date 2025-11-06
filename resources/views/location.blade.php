<x-layout.app :user="$user" nav="Location">
    <livewire:locations />
</x-layout.app>

<script>
    $(document).ready(function () {
        $('#training').DataTable({
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