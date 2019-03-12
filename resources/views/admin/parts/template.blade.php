@extends('layout.main')

@section('title', 'Admin - Home')

@section('content')
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<link href="https://cdn.datatables.net/rowreorder/1.2.0/css/rowReorder.dataTables.min.css" rel="stylesheet">
<main class="mn-inner">
    {!! $content !!}
</main>
<script type="text/javascript">

    $(document).ready(function (e) {
        $('.tooltipped').tooltip({delay: 50});
    });
</script>
@stop
