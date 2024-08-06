@extends('layout')

@section('page-title', 'Profile')
@section('page-content')
<div class="profile-container container">
    <div class="profile-info mb-4">
        <div class="profile-pic">

        </div>
        <div class="d-flex justify-content-between header">
            <div class="details row">
                <div class="col">
                    <p class="text-cap profile-name">{{ $user->userInfo->name }}</p>
                    <p class="text-cap"><i class='bx bx-map sub-text'></i>{{ $user->userInfo->state . ', ' . (str_contains($user->userInfo->city, 'City') ? $user->userInfo->city : $user->userInfo->city . ' City') }}</p>
                </div>
                @if($user->hasRole('PWD'))
                <div class="col">
                    <p class="text-cap age"><strong>Age:</strong>
                        {{ $user->userInfo->age }} years old
                    </p>
                    <p class="text-cap"> <strong>Disability:</strong>&nbsp;&nbsp;&nbsp;<span class="disability">{{ $user->userInfo->disability->disability_name }}</span></p>
                </div>
                @elseif($user->hasRole('Training Agency'))
                <div class="col">
                    <p class="text-cap age"><strong>Founder:</strong>
                        {{ $user->userInfo->founder }}
                    </p>
                    <p class="text-cap"> <strong>Year Established:</strong>
                        {{ $user->userInfo->year_established }}
                    </p>
                </div>
                @endif
                <div></div>
            </div>
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
                                                <input type="number" class="form-control" id="floatingInput" name="age" placeholder="Age" value="{{ $user->userInfo->age }}">
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
        </div>
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
                <h4 class="mb-3">About</h4>
                @if ($user->userInfo->about != null)
                <p>{!! nl2br(e($user->userInfo->about)) !!}</p>
                @else
                <p class="about sub-text">No data yet</p>
                @endif
            </div>
            @if ($user->hasRole('PWD'))
            <div class="bio-item exp">
                <div>
                    <h4 class="mb-3">Skills</h4>
                    <p>asaa</p>
                </div>
                <div>
                    <h4 class="mb-3">Education Level</h4>
                    <p class="match-info">{{$user->userInfo->education->education_name}}</p>
                </div>
            </div>
            <div class="bio-item exp">
                <div>
                    <h4 class="mb-3">Certifications</h4>
                    @forelse($certifications as $certification)
                    <p>
                        <a href="{{ route('download-certificate', $certification->id) }}" class="text-primary">
                            Certified {{$certification->program->title}}
                    </p>
                    @empty
                    <p class="about sub-text">No certifications yet. <a href="{{ route('pwd-list-program') }}">Enroll first!</a></p>
                    @endforelse
                </div>
                <div>
                    <div class="mb-2 d-flex">
                        <h4 class="">Work Experience&nbsp;&nbsp;</h4>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="border-0 match-info"><i class='bx bx-plus'></i></button>
                    </div>
                    <div>
                        <form action="{{route('add-experience')}}" method="POST">
                            @csrf
                            <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Work Experience</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="floatingInput" placeholder="Title" name="title">
                                                <label for="floatingInput">Title</label>
                                                @error('title')
                                                <span class="error-msg">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="">Date: </label>
                                                <input type="date" name="date" class="date-input">
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
                        <ul>
                            @forelse($experiences as $experience)
                            <li>
                                    <p class="exp-title">{{ $experience->title }}</p>
                                    <p class="exp-date">{{ \Carbon\Carbon::parse($experience->date)->format('M d, Y') }}</p>
                            </li>
                            @empty
                            <div class="about sub-text">No experiences. Add one.</div>
                        </ul>
                        @endforelse
                    </div>
                </div>
                @elseif($user->hasRole('Training Agency'))
                <div class="bio-item exp">
                    <div>
                        <h4 class="mb-3">Awards & Recognitions</h4>
                        @if ($user->userInfo->awards != null)
                        <p>{!! nl2br(e($user->userInfo->awards)) !!}</p>
                        @else
                        <p class="about sub-text">No data yet</p>
                        @endif
                    </div>
                    <div>
                        <h4 class="mb-3">Affiliations</h4>
                        @if ($user->userInfo->affiliations != null)
                        <p>{!! nl2br(e($user->userInfo->affiliations)) !!}</p>
                        @else
                        <p class="about sub-text">No data yet</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>

    @endsection

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchProvinces().then(() => {
                var selectedProvince = "{{ $user->userInfo->state }}";
                var provinceSelect = document.getElementById('provinceSelect');
                if (selectedProvince) {
                    provinceSelect.value = selectedProvince;
                    fetchCities(selectedProvince).then(() => {
                        var selectedCity = "{{ $user->userInfo->city }}";
                        var citySelect = document.getElementById('citySelect');
                        if (selectedCity) {
                            citySelect.value = selectedCity;
                        }
                    });
                }
            });

            document.getElementById('provinceSelect').addEventListener('change', function() {
                var provinceCode = this.value;
                fetchCities(provinceCode);
            });

            // Set max year for the year established input
            var yearEstablishedInput = document.getElementById('year-established');
            var currentYear = new Date().getFullYear();
            yearEstablishedInput.max = currentYear;
        });

        function fetchProvinces() {
            return fetch('https://psgc.cloud/api/provinces')
                .then(response => response.json())
                .then(data => {
                    var provinceSelect = document.getElementById('provinceSelect');
                    provinceSelect.innerHTML = '<option value="">Select Province</option>';
                    data.sort((a, b) => a.name.localeCompare(b.name));
                    data.forEach(province => {
                        var option = document.createElement('option');
                        option.value = province.name; // Ensure this matches your database value
                        option.text = province.name;
                        provinceSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching provinces:', error));
        }

        function fetchCities(provinceCode) {
            return fetch(`https://psgc.cloud/api/provinces/${provinceCode}/cities-municipalities`)
                .then(response => response.json())
                .then(data => {
                    var citySelect = document.getElementById('citySelect');
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    data.sort((a, b) => a.name.localeCompare(b.name));
                    data.forEach(city => {
                        var option = document.createElement('option');
                        option.value = city.name; // Ensure this matches your database value
                        option.text = city.name;
                        citySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching cities:', error));
        }
    </script>