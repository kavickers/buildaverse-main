@extends('layouts.app')

@section('title', 'Editing Item')

@section('content')
    <div class="row">
        <div class="col"></div>
        <div class="col-md-6">
            <div class="card p-0">
                <div class="card-header text-white font-weight-bold border-bottom">
                    Editing "{{ $item->name }}"
                </div>
                <div class="text-center">
                    <img src="/static/img/avatar.png" class="img-fluid w-250">
                </div>
                <div class="p-15">
                    <form action="{{ route('market.item.edit.post', $item->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">Name</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{ $item->name }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="(optional)">{{ $item->desc }}</textarea>
                        </div>
                        <div class="form-group">
                            <div class="custom-checkbox">
                                <input type="checkbox" id="offsale" name="offsale">
                                <label for="offsale">Off-sale</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cash">Cash</label>
                            <input class="form-control" id="cash" name="cash" value="{{ $item->cash }}">
                        </div>
                        <div class="form-group">
                            <label for="coins">Coins</label>
                            <input class="form-control" id="coins" name="coins" value="{{ $item->coins }}">
                        </div>
                        <input class="btn btn-success btn-block" type="submit" value="Update">
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
