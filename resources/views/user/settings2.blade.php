<?php
    function obfuscate_email($email)
    {
        $em   = explode("@",$email);
        $name = implode('@', array_slice($em, 0, count($em)-1));
        $len  = floor(strlen($name)/2);

        return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
    }
?>

@extends('layouts.app')

@section('title', 'Account Settings')

@section('modals')
    <!-- Change email modal -->
    <div class="modal" id="change_email" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" role="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
                <h5 class="modal-title">Change Email</h5>
                <!-- begin form -->
                <form method="POST" action="">
                    @csrf
                </form>
                <div class="text-right mt-20"> <!-- text-right = text-align: right, mt-20 = margin-top: 2rem (20px) -->
                    <a href="#" class="btn mr-5" role="button">Close</a>
                    <a href="#" class="btn btn-primary" role="button">I understand</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Change password modal -->
    <div class="modal" id="change_password" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" role="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
                <h5 class="modal-title">Change Password</h5>
                <!-- begin form -->
                <form method="POST" action="">
                    @csrf
                </form>
                <div class="text-right mt-20"> <!-- text-right = text-align: right, mt-20 = margin-top: 2rem (20px) -->
                    <a href="#" class="btn mr-5" role="button">Close</a>
                    <a href="#" class="btn btn-primary" role="button">I understand</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-eq-spacing justify-content-center">
        <div class="col-md-8">
            <h3 class="text-white font-weight-semi-bold">My Settings</h3>
        </div>
        <div class="col-md-8">

            <ul class="tabs border-left border-top border-right">
                <li class="tab col-4 active" data-tab-target="#general">GENERAL</li>
                <li class="tab col-4" data-tab-target="#security">SECURITY</li>
                <li class="tab col-4" data-tab-target="#privacy">PRIVACY</li>
                <!-- <li class="tab col-3" data-tab-target="#billing">BILLING</li> -->
            </ul>



            <div class="card rounded-0 p-15 m-0">

                <div class="tab-content">
                    <div id="general" class="active" data-tab-content>

                        <h5 class="font-weight-semi-bold">Account Info</h5>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="username" value="{{ \Illuminate\Support\Facades\Auth::user()->username }}" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" disabled><i class="fas fa-edit"></i></button>
                                </div>
                            </div>
                        </div>

                        <!--<p>Previous username(s): null</p> !-->

                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="username" value="**************" disabled>
                                <div class="input-group-append">
                                    <a href="#change_password" class="btn btn-primary" role="button"><i class="fas fa-edit"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email @if(\Illuminate\Support\Facades\Auth::user()->email_verified_at != NULL)<span class="text-success"><i class="far fa-check"></i> Verified</span>@else <span class="text-danger"><i class="far fa-times"></i> Not Verified</span>@endif</label>
                            <div class="input-group">
                                <input type="email" class="form-control" id="email" value="{{ obfuscate_email(\Illuminate\Support\Facades\Auth::user()->email) }}" disabled>
                                <div class="input-group-append">
                                    <a href="#change_email" class="btn btn-primary" role="button"><i class="fas fa-edit"></i></a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h5 class="font-weight-semi-bold">Personal</h5>

                        <form method="POST" action="{{ route('user.settings.update.general') }}">
                            @csrf
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" name="description" id="description">{{ nl2br(e(\Illuminate\Support\Facades\Auth::user()->biography)) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="signature">Forum Signature</label>
                                <input type="text" name="signature" class="form-control" value="{{ nl2br(\Illuminate\Support\Facades\Auth::user()->signature) }}">
                            </div>

                            <div class="form-group">
                                <label for="birthday">Birthday</label>
                                <input type="date" name="birthday" class="form-control" value="{{ \Illuminate\Support\Facades\Auth::user()->birthday }}" required="required">
                            </div>

                            <div class="form-group">
                                <label for="theme">Theme</label>
                                <select class="form-control" id="theme" name="theme" required="required">
                                    <option value="1" @if(\Illuminate\Support\Facades\Auth::user()->theme == 1)selected="selected"@endif>Dark</option>
                                    <option value="2" @if(\Illuminate\Support\Facades\Auth::user()->theme == 2)selected="selected"@endif>Light</option>
                                </select>
                            </div>

                            <div class="text-right">
                                <input class="btn btn-primary" type="submit" value="Save">
                            </div>
                        </form>

                    </div>
                    <div id="security" class="" data-tab-content>
                        <!--
                        <h5 class="font-weight-semi-bold">2-Step Verification</h5>

                        <form method="POST">
                            <div class="form-group">
                                <div class="custom-switch">
                                    <input type="checkbox" id="twostep">
                                    <label for="twostep"><span class="text-success"><i class="far fa-check"></i> Protected</span> <span class="text-danger"><i class="far fa-times"></i> Not Protected</span></label>
                                </div>
                            </div>
                            <p>When enabled, codes will be sent to your primary email address each time you log in from a new device.</p>
                            <p>When you log in from a new device, codes will be sent to l******@bloxcity.com</p>
                        </form>

                        <hr>
                        -->
                        <h5 class="font-weight-semi-bold">Secure Log Out</h5>
                        <p>Log out of all other sessions</p>
                        <input class="btn btn-primary" type="submit" value="Logout">

                    </div>
                    <div id="privacy" class="" data-tab-content>

                        <h5 class="font-weight-semi-bold">Privacy</h5>

                        <form method="POST" action="{{ route('user.settings.update.privacy') }}">
                            @csrf
                            <div class="form-group">
                                <label>Who can message me?</label>
                                <select class="form-control" name="message" id="message" required="required">
                                    <option value="1" @if(auth()->user()->privacy->message == 1)selected="selected"@endif>Everyone</option>
                                    <option value="2" @if(auth()->user()->privacy->message == 2)selected="selected"@endif>Friends</option>
                                    <option value="3" @if(auth()->user()->privacy->message == 3)selected="selected"@endif>Nobody</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Who can see my inventory?</label>
                                <select class="form-control" name="inventory" id="inventory" required="required">
                                    <option value="1" @if(auth()->user()->privacy->inventory == 1)selected="selected"@endif>Everyone</option>
                                    <option value="2" @if(auth()->user()->privacy->inventory == 2)selected="selected"@endif>Friends</option>
                                    <option value="3" @if(auth()->user()->privacy->inventory == 3)selected="selected"@endif>Nobody</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Who can see my current status?</label>
                                <select class="form-control" name="blurb" id="blurb" required="required">
                                    <option value="1" @if(auth()->user()->privacy->blurb == 1)selected="selected"@endif>Everyone</option>
                                    <option value="2" @if(auth()->user()->privacy->blurb == 2)selected="selected"@endif>Friends</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Who can trade with me?</label>
                                <select class="form-control" name="trade" id="trade" required="required">
                                    <option value="1" @if(auth()->user()->privacy->trade == 1)selected="selected"@endif>Everyone</option>
                                    <option value="2" @if(auth()->user()->privacy->trade == 2)selected="selected"@endif>Friends</option>
                                    <option value="3" @if(auth()->user()->privacy->trade == 3)selected="selected"@endif>Nobody</option>
                                </select>
                            </div>
                            <div class="text-right">
                                <input class="btn btn-primary" type="submit" value="Save">
                            </div>
                        </form>
                    </div>
                    <!--
                    <div id="billing" class="" data-tab-content>
                        <h5 class="font-weight-semi-bold">Billing</h5>
                        @if(\Illuminate\Support\Facades\Auth::user()->membership > 0)
                            <p>You currently have an active <b>{{ \Illuminate\Support\Facades\Auth::user()->get_membership() }}</b> membership expiring <b>{{ date(\Illuminate\Support\Facades\Auth::user()->membership_expires) }}</b> CST</p>
                        @else
                            <p>You currently have no active membership.</p>
                        @endif
                        <form method="post">
                            <div class="form-group">
                                <input class="btn btn-danger" type="submit" value="Cancel Renewal">
                            </div>
                        </form>

                        <div>
                            <span class="font-weight-semi-bold">Account Credit:</span>
                            <span class="font-weight-semi-bold cash">$0.00</span>
                        </div>
                    </div>
                    -->
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        @if(count($errors))
        @foreach($errors->all() as $error)
        window.onload(toastDangerAlert("Error", "<?php echo $error; ?>"));
        @endforeach
        @endif

        @if(session('success'))
        window.onload(toastSuccessAlert("Success", "<?php echo session('success'); ?>"));
        @endif
    </script>

    <script>
            /* Custom tab system */
            const tabs = document.querySelectorAll('[data-tab-target]');
            const tabContents = document.querySelectorAll('[data-tab-content]');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = document.querySelector(tab.dataset.tabTarget)
                    tabContents.forEach(tabContent => {
                        tabContent.classList.remove('active');
                    });
                    tabs.forEach(tab => {
                        tab.classList.remove('active');
                    });
                    tab.classList.add('active');
                    target.classList.add('active');
                });
            });
    </script>
@endsection
