@extends('layouts.app')

@section('title', 'Choose your item type')

@section('content')
    <div class="row">
        <div class="col"></div>
        <div class="col-md-6">
            <div class="card p-0">
                <div class="p-15">
                    <div class="row">
                        <div class="col-6 text-center">
                            <a href="/market/create/shirt" class="text-white">
                                <i class="fas fa-tshirt font-size-100"></i>
                                <h5 class="m-0 mt-5">Shirt</h5>
                            </a>
                        </div>
                        <div class="col-6 text-center">
                            <a href="/market/create/pants" class="text-white">
                                <i class="fas fa-socks font-size-100"></i>
                                <h5 class="m-0 mt-5">Pants</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col"></div>
    </div>
@endsection
