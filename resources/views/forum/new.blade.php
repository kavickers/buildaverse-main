<x-app-layout>
    <x-slot name="title">New Thread</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="card p-3 px-4 mb-3 mt-md-2 mobile-header">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('forum.index') }}" class="text-xl text-light">
                <i class="bi bi-chevron-left"></i>
            </a>
            @auth
                <div>
                    <a href="#" class="text-xl text-white" data-bs-toggle="tooltip" title="My Threads">
                        <i class="bi bi-chat-left-text"></i>
                    </a>
                </div>
            @endauth
        </div>
    </div>
    <h2>New Thread</h2>
    <div class="card mb-3 mt-3 mt-md-0">
        <div class="card-body pb-4">
            <form method="POST" action="{{ route('forum.thread.create.post') }}">
                @csrf
                <label class="text-muted fw-bold text-uppercase text-sm">
                    Topic:
                </label>
                <select class="form-control" id="topic" name="topic" required="required">\
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                    @endforeach
                </select>
                <div class="mb-2"></div>
                <label class="text-muted fw-bold text-uppercase text-sm">
                    Thread Title:
                </label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Title (max 50 characters)" value="{{ old('title') }}" required="required">
                <div class="mb-2"></div>
                <label class="text-muted fw-bold text-uppercase text-sm">
                    Thread Body:
                </label>
                <textarea class="form-control" id="body" name="body" placeholder="Body (max 3,000 characters)" maxlength="3000" rows="6">{{ old('body') }}</textarea>
                <div class="mb-3"></div>
                <div class="float-right">
                    <input class="btn btn-success" type="submit" value="Create">
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
