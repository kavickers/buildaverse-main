<x-app-layout>
    <x-slot name="title">Create Community</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h4> 
                <a href="{{ route('guilds.index') }}" class="text-sm align-middle text-muted me-2" data-bs-toggle="tooltip" title="Go back"><i class="bi bi-chevron-double-left fw-bold"></i></a>Create Community
            </h4>
            <div class="card card-body">
                <form method="POST" action="{{ route('guilds.create.post') }}" enctype="multipart/form-data">
                    @csrf
                    <label class="text-sm text-muted fw-bold">NAME:</label>
                    <input type="text" class="form-control mb-2" id="name" name="name" placeholder="Name" required/>
                    <label class="text-sm text-muted fw-bold">DESCRIPTION:</label>
                    <textarea class="form-control mb-3" rows="6" id="desc" name="desc" placeholder="(optional)"></textarea>
                    <div class="text-muted text-sm fw-bold">ICON:</div>
                    <div class="mb-2">
                        <input class="form-control" type="file" name="image" id="formFile" required/>
                    </div>
                    <button type="submit" class="btn btn-success">
                        Create (<i class="bi bi-cash-stack text-lg align-middle lh-1"></i>30)
                    </button> 
                </form>
            </div>
        </div>
    </div>
</x-app-layout>