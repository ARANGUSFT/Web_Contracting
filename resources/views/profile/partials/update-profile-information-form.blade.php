<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    @method('PATCH')

    <div class="card border-0 shadow mb-5">
        <div class="card-header bg-primary text-white fw-semibold">
            <i class="bi bi-person-lines-fill me-2"></i> Personal & Company Information
        </div>
        <div class="card-body">

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <div class="input-group">
                        <select class="form-select" name="phone_country_code" style="max-width: 100px;">
                            <option value="+1" {{ str_contains($user->phone, '+1') ? 'selected' : '' }}>🇺🇸 +1</option>
                            <option value="+52" {{ str_contains($user->phone, '+52') ? 'selected' : '' }}>🇲🇽 +52</option>
                            <option value="+57" {{ str_contains($user->phone, '+57') ? 'selected' : '' }}>🇨🇴 +57</option>
                        </select>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', preg_replace('/^\+\d+\s?/', '', $user->phone)) }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Language</label>
                    <select name="language" class="form-select">
                        <option value="English" {{ $user->language === 'English' ? 'selected' : '' }}>English</option>
                        <option value="Spanish" {{ $user->language === 'Spanish' ? 'selected' : '' }}>Spanish</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Profile Photo</label>
                    <input type="file" name="profile_photo" class="form-control">
                    @if ($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Photo" class="rounded mt-2" style="max-height: 90px;">
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $user->company_name) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Years of Experience</label>
                    <input type="text" name="years_experience" class="form-control" value="{{ old('years_experience', $user->years_experience) }}">
                </div>
            </div>

            <hr class="my-4">

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Residential Roof Types</label>
                    @foreach(['TPO', 'Low Slope', 'Tile', 'Wood Shakes', 'Asphalt Shingle', 'Metal'] as $roof)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="residential_roof_types[]" value="{{ $roof }}"
                                {{ in_array($roof, $user->residential_roof_types ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $roof }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="col-md-6">
                    <label class="form-label">Commercial Roof Types</label>
                    @foreach(['EPDM', 'Asphalt Shingle', 'Low Slope', 'TPO', 'Tar & Gravel', 'Metal'] as $roof)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="commercial_roof_types[]" value="{{ $roof }}"
                                {{ in_array($roof, $user->commercial_roof_types ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $roof }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr class="my-4">

            <div class="col-12">
                <label class="form-label">States You Can Work</label>
                <input type="text" name="states_you_can_work" class="form-control"
                    value="{{ old('states_you_can_work', is_array($user->states_you_can_work) ? implode(',', $user->states_you_can_work) : '') }}">
                <div class="form-check mt-2">
                    <input type="checkbox" name="all_states" class="form-check-input" value="1" {{ $user->all_states ? 'checked' : '' }}>
                    <label class="form-check-label">I can work in all states</label>
                </div>
            </div>

        </div>

        <div class="card-footer bg-light text-end">
            <button class="btn btn-primary px-4">Save Changes</button>
        </div>
    </div>
</form>
