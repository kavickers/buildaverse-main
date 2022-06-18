<x-app-layout>
    <x-slot name="title">Achievements</x-slot>
    <x-slot name="navigation"></x-slot>
    <h1 class="mb-4">Achievements</h1>

    <!-- Begin Category -->
    <h4>Official Achievements</h4>
    <div class="card mb-4">
        <div class="card-body">
            @foreach($badges as $badge)
                @if($badge->category == 1)
                    <!-- begin achievement -->
                        <div class="d-md-flex py-2">
                            <img class="pb-3" src="{{ $badge->image }}" height="125">
                            <div class="ms-md-3">
                                <h4 class="mb-0">{{ $badge->name }}</h4>
                                <p class="small text-muted mb-0">{{ \App\Models\UserBadge::where('badge_id', '=', $badge->id)->get()->count() }} users own this achievement</p>
                                @if($badge->enabled)
                                    <p class="small text-success mb-1">This achievement is currently obtainable.</p>
                                @else
                                    <p class="small text-danger mb-1">This achievement is no longer obtainable.</p>
                                @endif
                                <p>{{ $badge->description }}</p>
                            </div>
                        </div>
                    <!-- end achievement -->
                @endif
            @endforeach
        </div>
    </div>
    <!-- End category -->

    <!-- Begin Category -->
    <h4>Friendship Milestones</h4>
    <div class="card mb-4">
        <div class="card-body">
        @foreach($badges as $badge)
            @if($badge->category == 2)
                <!-- begin achievement -->
                    <div class="d-md-flex py-2">
                        <img class="pb-3" src="{{ $badge->image }}" height="125">
                        <div class="ms-md-3">
                            <h4 class="mb-0">{{ $badge->name }}</h4>
                            <p class="small text-muted mb-0">{{ \App\Models\UserBadge::where('badge_id', '=', $badge->id)->get()->count() }} users own this achievement</p>
                            @if($badge->enabled)
                                <p class="small text-success mb-1">This achievement is currently obtainable.</p>
                            @else
                                <p class="small text-danger mb-1">This achievement is no longer obtainable.</p>
                            @endif
                            <p>{{ $badge->description }}</p>
                        </div>
                    </div>
                    <!-- end achievement -->
                @endif
            @endforeach
        </div>
    </div>
    <!-- End category -->

    <!-- Begin Category -->
    <h4>World Milestones</h4>
    <div class="card mb-4">
        <div class="card-body">
        @foreach($badges as $badge)
            @if($badge->category == 3)
                <!-- begin achievement -->
                    <div class="d-md-flex py-2">
                        <img class="pb-3" src="{{ $badge->image }}" height="125">
                        <div class="ms-md-3">
                            <h4 class="mb-0">{{ $badge->name }}</h4>
                            <p class="small text-muted mb-0">{{ \App\Models\UserBadge::where('badge_id', '=', $badge->id)->get()->count() }} users own this achievement</p>
                            @if($badge->enabled)
                                <p class="small text-success mb-1">This achievement is currently obtainable.</p>
                            @else
                                <p class="small text-danger mb-1">This achievement is no longer obtainable.</p>
                            @endif
                            <p>{{ $badge->description }}</p>
                        </div>
                    </div>
                    <!-- end achievement -->
                @endif
            @endforeach
        </div>
    </div>
    <!-- End category -->

    <div class="mb-md-5">&nbsp;</div>
</x-app-layout>
