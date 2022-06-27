<x-app-layout>
    <x-slot name="title">My Friends</x-slot>
    <x-slot name="navigation"></x-slot>
    <div class="text-3xl fw-semibold mb-2">My Friends</div>
    <div class="card p-3 mb-3">
        <ul class="nav nav-pills nav-justified" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="friends" data-bs-toggle="pill" data-bs-target="#friends-tab" type="button" role="tab" aria-controls="friends" aria-selected="true">
                    My Friends ({{ auth()->user()->get_short_num(auth()->user()->getFriends()->count()) }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="incomingFriends" data-bs-toggle="pill" data-bs-target="#incoming-tab" type="button" role="tab" aria-controls="incoming" aria-selected="false">
                    Incoming Requests ({{ auth()->user()->get_short_num(auth()->user()->getFriendRequests()->count()) }})
                </button>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane show active" id="friends-tab" role="tabpanel" aria-labelledby="friends">
                <div class="row gy-3">
                    @include('components.load_my_friends')
                </div>
                <div class="d-flex">
                    {{ $friends->links('vendor.pagination.default') }}
                </div>
            </div>
            <div class="tab-pane" id="incoming-tab" role="tabpanel" aria-labelledby="incomingFriends">
                <div class="row gy-3">
                    @include('components.load_my_friend_requests')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>