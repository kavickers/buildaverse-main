@extends('layouts.app')

@section('title', 'File a report')

@section('content')
    <div class="row row-eq-spacing justify-content-center">
        <div class="col-md-6">
            <div class="card p-0">
                <div class="card-header text-truncate font-weight-bold border-bottom">
                    Tell us how you think this content is breaking the rules
                </div>
                <div class="row p-15">
                    <div class="col-md-12">

                        <form action="{{ route($route, $rid) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <input type="hidden" name="rid" value="{{ $rid }}">
                            <input type="hidden" name="uid" value="{{ $uid }}">
                            <div class="form-group">
                                <label for="rule" class="required">Which rule does this content violate?</label>
                                <select class="form-control" name="rule" id="rule">
                                    <option value="" selected="selected" disabled="disabled">Please select a category</option>
                                    <option value="Spam">Spam</option>
                                    <option value="Excessive Profanity">Excessive Profanity</option>
                                    <option value="Sexual Content">Sexual Content</option>
                                    <option value="Sensitive Topics">Sensitive Topics</option>
                                    <option value="Offsite Links">Offsite Links</option>
                                    <option value="Harassment / Discrimination">Harassment / Discrimination</option>
                                    <option value="Exploiting / Cheating">Exploiting / Cheating</option>
                                    <option value="Account Theft - Phishing / Hacking">Account Theft - Phishing / Hacking</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="text-center">
                                    <input class="btn btn-success" type="submit" value="File Report">
                                </div>
                            </div>
                        </form>

                        <script type="text/javascript">

                            @if(count($errors))
                                @foreach($errors->all() as $error)
                                    toastDangerAlert("Error", "<?php echo $error; ?>");
                                @endforeach
                            @endif

                            @if(session('status'))
                                toastDangerAlert("Error", "<?php echo session('status'); ?>");
                            @endif

                            function toastDangerAlert(title, content) {
                                halfmoon.initStickyAlert({
                                    content: content,
                                    title: title,
                                    alertType: "alert-danger",
                                    fillType: "filled"
                                });
                            }
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
