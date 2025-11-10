<x-layout.app :user="$user" nav="Training Event Participants">
    <livewire:event-participant :id="$id" :user="$user" />
</x-layout.app>

<script>
    $(document).ready(function () {
        $('#event-participant').DataTable({
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
    $(document).ready(function () {
        $('#history-approval').DataTable({
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