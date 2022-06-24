<x-app-layout>
    <x-slot name="title">New Item</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="row">
        <div class="col"></div>
        <div class="col-md-6">
            <div class="card p-0">
                <div class="p-4">
                    <div class="row">
                        <div class="col-6 text-center">
                            <a href="/market/create/shirt" class="text-white">
                                <i class="fas fa-tshirt text-5xl"></i>
                                <h5 class="m-0 mt-2">Shirt</h5>
                            </a>
                        </div>
                        <div class="col-6 text-center">
                            <a href="/market/create/pants" class="text-white">
                                <i class="fas fa-socks text-5xl"></i>
                                <h5 class="m-0 mt-2">Pants</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
</x-app-layout>