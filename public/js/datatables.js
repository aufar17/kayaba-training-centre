$(document).ready(function () {
    var currentMonth = new Date().getMonth() + 1;
    var currentYear = new Date().getFullYear();

    $("#filterMonth").val(currentMonth);
    $("#filterYear").val(currentYear);

    $("#example").DataTable({
        paging: false,
        info: false,
        searching: true,
        pageLength: 10,
        paging: true,
        lengthChange: true,
        order: [[0, "asc"]],
    });
    $("#monthlyAbnormality").DataTable({
        paging: false,
        info: false,
        searching: true,
        pageLength: 10,
        paging: true,
        lengthChange: true,
        order: [[0, "asc"]],
    });

    $("#example_filter").appendTo("#tableHeader").addClass("ms-auto");
});
