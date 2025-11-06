<x-layout.app :user="$user" nav="Trainer">
    <livewire:trainers />
</x-layout.app>

<script>
    $(document).ready(function () {
        $('#trainer').DataTable({
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