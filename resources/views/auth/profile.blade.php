@extends('layout')

@section('page-title', 'Profile')
@section('page-content')
<div class="profile-container container">
    <div class="profile-info mb-4">
        <div class="profile-pic">

        </div>
        <div class="d-flex justify-content-between header">
            <div class="details">
                <div>
                    <p class="text-cap profile-name">{{ $user->userInfo->name }}</p>
                    <p class="text-cap"><i class='bx bx-map sub-text'></i>{{ $user->userInfo->state . ', ' . (str_contains($user->userInfo->city, 'City') ? $user->userInfo->city : $user->userInfo->city . ' City') }}</p>
                </div>
                @unless($user->userInfo->disability_id == 1)
                <div>
                    <p class="text-cap age"><strong>Age:</strong>
                        @if ($user->userInfo->age != null)
                        {{ $user->userInfo->age }} years old
                        @else
                        <span class="sub-text">No data yet</span>
                        @endif
                    </p>
                    <p class="text-cap"> <strong>Disability:</strong>&nbsp;&nbsp;&nbsp;<span class="disability">{{ $user->userInfo->disability->disability_name }}</span></p>
                </div>
                @endunless
                <div></div>
            </div>
            @if (Auth::id() == $user->id)
            <form action="{{ route('edit-profile') }}" method="POST">
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

                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="floatingInput" name="name" placeholder="Name" value="{{ $user->userInfo->name }}">
                                        <label for="floatingInput">Name</label>
                                        @error('name')
                                        <span class="error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="floatingInput" name="email" placeholder="Email" value="{{ $user->email }}" disabled>
                                        <label for="floatingInput">Email Address</label>
                                        @error('email')
                                        <span class="error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="floatingInput" name="contactnumber" placeholder="Contact Number" value="{{ $user->userInfo->contactnumber }}">
                                                <label for="floatingInput">Contact Number</label>
                                                @error('contactnumber')
                                                <span class="error-msg">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        @unless($user->userInfo->disability_id == 1)
                                        <div class="col">
                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" id="floatingInput" name="age" placeholder="Age" value="{{ $user->userInfo->age }}">
                                                <label for="floatingInput">Age</label>
                                                @error('age')
                                                <span class="error-msg">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        @endunless
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="floatingInput" name="state" placeholder="State" value="{{ $user->userInfo->state }}">
                                                <label for="floatingInput">State</label>
                                                @error('state')
                                                <span class="error-msg">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="floatingInput" name="city" placeholder="City" value="{{ $user->userInfo->city }}">
                                                <label for="floatingInput">City</label>
                                                @error('city')
                                                <span class="error-msg">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @unless($user->userInfo->disability_id == 1)
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="floatingSelect" name="disability" aria-label="Floating label select example">
                                            @foreach ($disabilities as $disability)
                                            <!-- @if ($disability->disability_name != 'Not Applicable') -->
                                            <option value="{{ $disability->id }}" @if ($user->userInfo->disability_id == $disability->id ) selected @endif >{{ $disability->disability_name }}</option>
                                            <!-- @endif      -->
                                            @endforeach

                                        </select>
                                        <label for="floatingSelect">Disability</label>
                                    </div>
                                    @endunless
                                    <hr>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="About Me" id="floatingTextarea2" name="about" style="height: 200px">{{ $user->userInfo->about }}</textarea>
                                        <label for="floatingTextarea2">About Me</label>
                                        @error('about')
                                        <span class="error-msg">{{ $message }}</span>
                                        @enderror
                                    </div>

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
        </div>
        @endif
    </div>
    <div class="more-details d-flex">
        <div class="contact border">
            <h4 class="mb-4">Contact Information</h4>
            <div class="contact-container">
                <div class="contact-item ">
                    <span class="d-flex align-items-center sub-text"><i class='bx bx-envelope side-icon'></i> Email</span>
                    <p><a href="">{{ $user->email }}</a></p>
                </div>
                <div class="contact-item">
                    <span class="d-flex align-items-center sub-text"><i class='bx bx-envelope side-icon'></i> Contact no</span>
                    <p><a href="">{{ $user->userInfo->contactnumber }}</a></p>
                </div>
                <div class="contact-item">
                    <span class="d-flex align-items-center sub-text"><i class='bx bxl-facebook  side-icon'></i> Facebook</span>
                    <p><a href="">{{ $user->email }}</a></p>
                </div>
                <div class="contact-item">
                    <span class="d-flex align-items-center sub-text"><i class='bx bxl-instagram side-icon'></i> Instagram</span>
                    <p><a href="">{{ $user->email }}</a></p>
                </div>
                <div class="contact-item ">
                    <span class="d-flex align-items-center sub-text"><i class='bx bx-globe side-icon'></i> Website</span>
                    <p><a href="">{{ $user->email }}</a></p>
                </div>
            </div>
        </div>
        <div class="bio">
            <div class="bio-item">
                <h4 class="mb-3">About Me</h4>
                @if ($user->userInfo->about != null)
                <p>{{ $user->userInfo->about }}</p>
                @else
                <p class="about sub-text">No data yet</p>
                @endif
            </div>
            <div class="bio-item exp">
                <div>
                    <h4 class="mb-3">Skills</h4>
                    <p>asaa</p>
                </div>
                <div>
                    <h4 class="mb-3">Education Level</h4>
                    <p>asaa</p>
                </div>
            </div>
            <div class="bio-item exp">
                <div>
                    <h4 class="mb-3">Certifications</h4>
                    <p>asaa</p>
                </div>
                <div>
                    <h4 class="mb-3">Work Experience</h4>
                    <p>asaa</p>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection