<?php
function obfuscate_email($email)
{
    $em   = explode("@",$email);
    $name = implode('@', array_slice($em, 0, count($em)-1));
    $len  = floor(strlen($name)/2);

    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
}
?>

<x-app-layout>
    <x-slot name="title">Account Settings</x-slot>
    <x-slot name="navigation"></x-slot>

    <div class="row">
        <h1 class="mb-4">Account Settings</h1>
        <div class="d-md-flex align-items-start">

            <div class="flex-column mb-3 mb-md-0">
                <div class="nav nav-pills me-sm-3 card p-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home"
                            type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">General</button>
                    <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile"
                            type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Privacy</button>
                    <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages"
                            type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Security</button>
                    <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings"
                            type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Connections</button>
                </div>
                <div class="card me-sm-3 mt-3 p-3 text-center text-small">
                    <h3><i class="bi bi-patch-question-fill"></i></h3>
                    Need help?
                    <a href="#">Support -></a>
                    <a href="#">FAQ -></a>
                </div>
            </div>

            <div class="tab-content card card-body" id="v-pills-tabContent">
                <div class="tab-pane show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <div class="float-right"><a href="#">Change</a></div>
                    <h5 class="mb-1">Username</h5>
                    <p class="mb-3 text-bold">{{ auth()->user()->username }}</p>
                    <div class="float-right"><a href="#">Edit</a></div>
                    <h5 class="mb-1">Email @if(auth()->user()->email_verified_at == NULL) <span class="text-danger text-sm"><i class="bi bi-x-circle" style="vertical-align: 0.02rem;"></i> Not Verified <a href="#">Resend?</a></span> @else <span class="text-success text-sm"><i class="bi bi-check-circle" style="vertical-align: 0.02rem;"></i> Verified</span> @endif </h5>
                    <p class="mb-3 text-bold">{{ obfuscate_email(auth()->user()->email) }}</p>
                    <form method="POST" action="{{ route('user.settings.update.general') }}">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="description" class="mb-1">Description</label>
                            <textarea class="form-control" name="description" id="description" placeholder="Your biography..." rows="3">{{ nl2br(e(auth()->user()->biography)) }}</textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label for="signature" class="mb-1">Forum Signature</label>
                            <input type="text" name="signature" class="form-control" value="{{ nl2br(auth()->user()->signature) }}">
                        </div>

                        <div class="form-group mb-2">
                            <label for="birthday" class="mb-1">Birthday</label>
                            <input type="date" name="birthday" class="form-control" value="{{ auth()->user()->birthday }}" required="required">
                        </div>

                        <div class="form-group mb-2">
                            <label for="theme" class="mb-1">Theme</label>
                            <select class="form-control" id="theme" name="theme" required="required">
                                <option value="1" @if(auth()->user()->theme == 1)selected="selected"@endif>Dark</option>
                            </select>
                        </div>

                        <div class="float-right">
                            <input class="btn btn-primary" type="submit" value="Save">
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <h5 class="mb-1">Privacy Settings</h5>
                    <form method="POST" action="{{ route('user.settings.update.privacy') }}">
                        @csrf
                        <div class="form-group mb-2">
                            <label class="mb-1">Who can message me?</label>
                            <select class="form-control" name="message" id="message" required="required">
                                <option value="1" @if(auth()->user()->privacy->message == 1)selected="selected"@endif>Everyone</option>
                                <option value="2" @if(auth()->user()->privacy->message == 2)selected="selected"@endif>Friends</option>
                                <option value="3" @if(auth()->user()->privacy->message == 3)selected="selected"@endif>Nobody</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1">Who can see my inventory?</label>
                            <select class="form-control" name="inventory" id="inventory" required="required">
                                <option value="1" @if(auth()->user()->privacy->inventory == 1)selected="selected"@endif>Everyone</option>
                                <option value="2" @if(auth()->user()->privacy->inventory == 2)selected="selected"@endif>Friends</option>
                                <option value="3" @if(auth()->user()->privacy->inventory == 3)selected="selected"@endif>Nobody</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1">Who can see my current status?</label>
                            <select class="form-control" name="blurb" id="blurb" required="required">
                                <option value="1" @if(auth()->user()->privacy->blurb == 1)selected="selected"@endif>Everyone</option>
                                <option value="2" @if(auth()->user()->privacy->blurb == 2)selected="selected"@endif>Friends</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1">Who can trade with me?</label>
                            <select class="form-control" name="trade" id="trade" required="required">
                                <option value="1" @if(auth()->user()->privacy->trade == 1)selected="selected"@endif>Everyone</option>
                                <option value="2" @if(auth()->user()->privacy->trade == 2)selected="selected"@endif>Friends</option>
                                <option value="3" @if(auth()->user()->privacy->trade == 3)selected="selected"@endif>Nobody</option>
                            </select>
                        </div>
                        <div class="float-right">
                            <input class="btn btn-primary" type="submit" value="Save">
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                    <h5>Secure Log Out</h5>
                    <p>Log out of all other sessions</p>
                    <input class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#logoutSessionsModal" value="Logout">
                </div>
                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <h5 class="mb-2">Connections</h5>
                    @if(auth()->user()->hasLinkedDiscord())
                        <div class="d-inline-block rounded border-1 border-dark border p-2">
                            <div class="d-inline-block"><img src="https://cdn.discordapp.com/avatars/{{ auth()->user()->discord->id }}/{{ auth()->user()->discord->avatar }}.webp?size=32" alt="{{ auth()->user()->discord->username }}#{{ auth()->user()->discord->discriminator }}" class="img-fluid rounded-circle"></div>
                            <div class="d-inline-block">{{ auth()->user()->discord->username }}<span class="text-muted">#{{ auth()->user()->discord->discriminator }}</span></div>
                            <a href="#" class="text-danger text-sm" onclick="event.preventDefault();document.getElementById('unlink-discord').submit();">Unlink</a>
                            <form method="POST" id="unlink-discord" action="{{ route('discord.unlink') }}" class="d-none">
                                @csrf
                            </form>
                        </div>
                    @else
                        <a href="{{ route('discord.connect') }}" class="btn btn-primary" style="background-color:#5865F2!important;"><i class="fa-brands fa-discord"></i> Discord</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Log out of sessions modal -->
    <div class="modal fade" id="logoutSessionsModal" tabindex="-1" aria-labelledby="logoutSessionsModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body">
                    <h5 class="mb-2">
                        Verify Password
                    </h5>
                    <form action="{{ route('user.settings.logoutall') }}" method="POST">
                        @csrf
                        <input class="form-control mb-2" type="password" placeholder="Password" name="password">
                        <input class="btn btn-primary float-right" type="submit" value="Logout">
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- end modal -->
</x-app-layout>
