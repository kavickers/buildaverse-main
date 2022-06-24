<x-app-layout>
    <x-slot name="title">Page Title</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h4>
                <a href="#" class="text-sm align-middle text-muted me-2" data-bs-toggle="tooltip" title="Go back"><i class="bi bi-chevron-double-left fw-bold"></i></a>Create Community
            </h4>
            <div class="card card-body">
                <label class="text-sm text-muted fw-bold">NAME:</label>
                <input type="text" class="form-control mb-2" placeholder="Buildaverse Pirates" />
                <label class="text-sm text-muted fw-bold">DESCRIPTION:</label>
                <textarea class="form-control mb-3" rows="6" placeholder="Arr Pirate!"></textarea>
                <div class="text-muted text-sm fw-bold">ICON:</div>
                <div class="mb-2">
                    <input class="form-control" type="file" id="formFile" />
                </div>
                <button type="submit" class="btn btn-success">
                    Create (<i class="bi bi-cash-stack text-lg align-middle lh-1"></i>30)
                </button>
            </div>
        </div>
    </div>
</x-app-layout>