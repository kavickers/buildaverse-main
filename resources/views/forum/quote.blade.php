<x-app-layout>
    <x-slot name="title">Quoting "{{ $quote->owner->username }}" on "{!! nl2br(e($thread->title)) !!}"</x-slot>
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
    <h2>Quoting "{{ $quote->owner->username }}" on "{!! nl2br(e($thread->title)) !!}"</h2>
    <div class="card mb-3 mt-3 mt-md-0">
        <div class="card-body pb-4">
            <form method="POST" action="{{ route('forum.thread.quote.post', ['thread' => $thread->id, 'quote_id' => $quote->id, 'quote_type' => $quote_type]) }}">
                @csrf
                <label class="text-muted fw-bold text-uppercase text-sm">
                    Reply Body:
                </label>
                <textarea class="form-control" id="body" name="body" placeholder="Body (max 3,000 characters)" maxlength="3000" rows="6">{{ old('body') }}</textarea>
                <div class="mb-3"></div>
                <div class="float-right">
                    <input class="btn btn-success" type="submit" value="Reply">
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
