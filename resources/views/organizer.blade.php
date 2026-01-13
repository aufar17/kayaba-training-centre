<x-layout.app :user="$user" nav="Organizer">
    <livewire:organizers />
</x-layout.app>

<script>
    $(document).ready(function () {
        $('#organizer').DataTable({
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