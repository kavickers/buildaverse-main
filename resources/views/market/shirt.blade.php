<x-app-layout>
    <x-slot name="title">Create Shirt</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="row">
        <div class="col"></div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white font-weight-medium">
                    Create Shirt
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7">
                            <form action="{{ route('market.create.shirt.process') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="text-sm mb-1 text-muted fw-bold">NAME:</label>
                                <input type="text" class="form-control mb-2" id="title" name="title" placeholder="Name" required>
                                <label class="text-sm mb-1 text-muted fw-bold">DESCRIPTION:</label>
                                <textarea class="form-control mb-3" rows="6" id="description" name="description" placeholder="(optional)"></textarea>
                                <div class="my-2">
                                    <input class="form-control" type="file" name="image" id="formFile" required>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    Upload
                                </button>
                            </form>
                        </div>
                        <div class="col-md-5">
                            <img src="https://cdn.buildaverse.net/shirt_template.png" class="img-fluid mx-auto rounded mt-2 mt-md-0 mb-2">
                            <a href="https://cdn.buildaverse.net/shirt_template.png" target="_blank" class="btn btn-primary d-block text-center"><i class="bi bi-box-arrow-down me-2"></i>Download Template</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
</x-app-layout>