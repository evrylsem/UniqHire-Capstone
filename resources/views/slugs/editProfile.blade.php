<form action="{{ route('edit-profile') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div>
        <button type="button" class="submit-btn border-0" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Edit Profile</button>
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">

                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Profile</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-danger">
                            @if (!empty($user->userInfo->profile_path))
                            Current file: {{ basename($user->userInfo->profile_path) }}
                            @endif
                        </div>
                        <div class="input-group mb-3">
                            <input type="file" name="profile_picture" class="form-control" id="choose-file" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                            <button class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon04" onclick="clearFileInput('choose-file')">Remove</button>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput" name="name" placeholder="Name" value="{{ $user->userInfo->name }}">
                            <label for="floatingInput">Name</label>
                            @error('name')
                            <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="floatingInput" name="email" placeholder="Email" value="{{ $user->email }}" disabled>
                                    <label for="floatingInput">Email Address</label>
                                    @error('email')
                                    <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" name="contactnumber" placeholder="Contact Number" value="{{ $user->userInfo->contactnumber }}">
                                    <label for="floatingInput">Contact Number</label>
                                    @error('contactnumber')
                                    <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @if($user->hasRole('PWD'))
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="floatingInput" name="age" placeholder="Age" value="{{ $user->userInfo->age}}">
                                    <label for="floatingInput">Age</label>
                                    @error('age')
                                    <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="education-level" name="education" aria-label="Floating label select example">
                                        @foreach ($levels as $level)
                                        @if ($level->id != '1')
                                        <option value="{{ $level->id }}" @if ($user->userInfo->educational_id == $level->id ) selected @endif>{{ $level->education_name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    <label for="education-level">Education Level</label>
                                </div>
                            </div>
                            @elseif ($user->hasRole('Training Agency'))
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" name="founder" value="{{ $user->userInfo->founder }}" placeholder="Founder">
                                    <label for="floatingInput">Founder</label>
                                    @error('founder')
                                    <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="year-established" name="year_established" value="{{ $user->userInfo->year_established }}" min="1000" max="">
                                    <label for="year-established">Year Established</label>
                                    @error('year-established')
                                    <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <select type="text" class="form-select" id="provinceSelect" name="state" placeholder="Province">
                                        <option value="">Select Province</option>
                                    </select>
                                    <label for="provinceSelect">Province</label>
                                    @error('state')
                                    <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating mb-3">
                                    <select type="text" class="form-select" id="citySelect" name="city" placeholder="City">
                                        <option value="">Select City</option>
                                    </select>
                                    <label for="citySelect">City</label>
                                    @error('city')
                                    <span class="error-msg">{{ $message }}</span>
                                    @enderror
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if($user->hasRole('PWD'))
                        <div class="form-floating mb-3">
                            <select class="form-select" id="floatingSelect" name="disability" aria-label="Floating label select example">
                                @foreach ($disabilities as $disability)
                                @if ($disability->disability_name != 'Not Applicable')
                                <option value="{{ $disability->id }}" @if ($user->userInfo->disability_id == $disability->id ) selected @endif >{{ $disability->disability_name }}</option>
                                @endif
                                @endforeach

                            </select>
                            <label for="floatingSelect">Disability</label>
                        </div>
                        @endif
                        <hr>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="About" id="floatingTextarea2" name="about" style="height: 200px">{{ $user->userInfo->about }}</textarea>
                            <label for="floatingTextarea2">About</label>
                            @error('about')
                            <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                        @if($user->hasRole('Training Agency'))
                        <hr>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="About" id="floatingTextarea2" name="awards" style="height: 100px">{{ $user->userInfo->awards }}</textarea>
                            <label for="floatingTextarea2">Awards</label>
                            @error('awards')
                            <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="About" id="floatingTextarea2" name="affiliations" style="height: 100px">{{ $user->userInfo->awards }}</textarea>
                            <label for="floatingTextarea2">Affiliations</label>
                            @error('affiliations')
                            <span class="error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="deny-btn border-0">Clear</button>
                        <button type="submit" class="border-0 submit-btn">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>