<x-layout.app :user="$user" nav="Participant History">
    <livewire:participant-history :npk="$npk" :user="$user" />
</x-layout.app>

<script>
    $(document).ready(function () {
        $('#participant-history').DataTable({
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