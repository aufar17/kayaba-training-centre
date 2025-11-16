<x-layout.app :user="$user" nav="Training List">
    <livewire:trainings />
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
            pageLength: 25,      
            order: [[0, "asc"]],
        });
    });
</script>