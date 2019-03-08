@extends('cms.layout')
 
@section('content')
    <div class="table-responsive-sm">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Image</th>
                <th scope="col">Filename</th>
                <th scope="col">Original Filename</th>
                <th scope="col">Resized Filename</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($photos as $photo)
                <tr>
                    <td><img src="{{ asset('/cms/images/'.$photo->resized_name) }}"></td>
                    <td>{{ $photo->filename }}</td>
                    <td>{{ $photo->original_name }}</td>
                    <td>{{ $photo->resized_name }}</td>
                    <td><button class="btn btn-danger delete-img-btn" type="button" data-name="{{ $photo->original_name }}">Delete</button></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        $(document).on('click', '.delete-img-btn', function(e) {
            var _this = $(this);
          //  console.log({id: $(this).data('name')});
            $.ajax({
                type: 'post',
                url: './images-delete',
                data: {id: _this.data('name')},
                dataType: 'json',
                success: function (data) {
                    window.location.reload();
                },
                error(a, b, c) {
                    console.log(c);
                }
            });
        });
    </script>
@endsection