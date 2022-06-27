<x-app-layout>
    <x-slot name="title">Friends</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="text-3xl fw-semibold mb-2">Friends</div>
    <div class="row gy-3">
        @foreach($friends as $friend)
            <div class="col-md-6 col-lg-4">
                <div class="card p-3">
                    <div class="d-flex align-items-center">
                        <img src="/img/avatar/headshot.png" class="headshot rounded-circle me-3" width="80" />
                        <div class="w-100 min-w-0">
                            <a href="{{ route('user.profile', $friend->id) }}" class="d-block truncate text-xl fw-semibold text-light">{{ $friend->username }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="d-flex">
            {{ $friends->links('vendor.pagination.default') }}
        </div>
    </div>
</x-app-layout>