@once
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
@endonce

<script>
    $(document).ready(function () {
        let tableId    = '#{{ $tableId ?? "listing-table" }}';
        let pageLength = {{ $pageLength ?? 10 }};
        let lengthMenu = {!! json_encode($lengthMenu ?? [10, 25, 50, 100]) !!};
        let order      = {!! json_encode($order ?? [[0, 'asc']]) !!};
        let columnDefs = {!! json_encode($columnDefs ?? []) !!};

        $(tableId).DataTable({
            pageLength: pageLength,
            lengthMenu: lengthMenu,
            order: order,
            columnDefs: columnDefs
        });
    });
</script>
