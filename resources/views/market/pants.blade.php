@extends('layouts.app')

@section('title', 'Create Pants')

@section('content')
    <div class="row">
        <div class="col"></div>
        <div class="col-md-6">
            <div class="card p-0">
                <div class="card-header text-white font-weight-medium">
                    Create Pants
                </div>
                <div class="p-15">
                    <form action="{{ route('market.create.pants.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="title" class="required">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Title" required="required">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="(optional)"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="image" class="required">Template</label>
                            <div class="custom-file">
                                <input type="file" id="image" name="image" required="required">
                                <label for="image">Choose file</label>
                            </div>
                        </div>
                        <input class="btn btn-success btn-block" type="submit" value="Create">
                    </form>

                    <script type="text/javascript">

                        @if(count($errors))
                            @foreach($errors->all() as $error)
                                toastDangerAlert("Error", "<?php echo $error; ?>");
                            @endforeach
                        @endif

                        function toastDangerAlert(title, content) {
                            halfmoon.initStickyAlert({
                                content: content,
                                title: title,
                                alertType: "alert-danger",
                                fillType: "filled"
                            });
                        }
                    </script>
                </div>
            </div>

        </div>
        <div class="col"></div>
    </div>
@endsection
