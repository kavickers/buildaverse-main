<x-app-layout>
    <x-slot name="title">Submit Report</x-slot>
    <x-slot name="navigation"></x-slot>

    <div class="row row-eq-spacing justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header text-truncate font-weight-bold border-bottom">
                    Tell us how you think this is breaking the rules
                </div>
                <form action="{{ route($route, $rid) }}" method="POST" class="p-3">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <input type="hidden" name="rid" value="{{ $rid }}">
                    <input type="hidden" name="uid" value="{{ $uid }}">
                    <div class="form-group">
                        <label for="rule" class="required mb-1">Which rule does this content violate?</label>
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
                        <div class="text-center mt-2">
                            <input class="btn btn-success" type="submit" value="File Report">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>