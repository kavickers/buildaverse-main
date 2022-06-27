@foreach($blurbs as $blurb)
    <div class="section">
        <div class="d-flex align-items-center gap-3 position-relative">
            <a href="{{ route('user.profile', $blurb->owner->id) }}" class="shrink">
                <img src="img/avatar/headshot.png" class="img-fluid rounded-circle headshot" width="70" />
            </a>
            <div>
                <div>
                    <a href="{{ route('user.profile', $blurb->owner->id) }}" class="text-xl fw-semibold text-light">{{ $blurb->owner->username }}</a><span class="text-sm ms-2 text-muted">{{ $blurb->created_at->diffForHumans() }}</span>
                </div>
                <div>{{ $blurb->text }}</div>
            </div>
            <div class="dropdown position-absolute top-0 end-0 text-danger">
                <button class="text-xl bg-transparent border-0 p-0 text-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical text-xl"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1" data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(0px, 33px);">
                    <li>
                        <span class="text-center dropdown-item-text notification-dropdown-title p-0">Actions</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider mb-1">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('report.blurb', $blurb->id) }}">Report</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endforeach