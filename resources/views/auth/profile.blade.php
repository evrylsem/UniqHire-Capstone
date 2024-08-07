@extends('layout')

@section('page-title', 'Profile')
@section('page-content')
<div class="profile-container container">
    <div class="profile-info mb-4">
    <div class="profile-pic" style="background-image: url('{{ asset($user->userInfo->profile_path) }}')">

            @include("slugs.addProfile")
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
            @include('slugs.editProfile')
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
                    @include('slugs.editSkills')
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
                    @include('slugs.editExperiences')
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
                        option.value = city.name.trim(); // Ensure this matches your database value
                        option.text = city.name.trim();
                        citySelect.appendChild(option);
                    });

                    var userCity = "{{ $user->userInfo->city }}".trim().toLowerCase();

                    Array.from(citySelect.options).forEach(option => {
                        if (option.value.trim().toLowerCase() === userCity) {
                            option.selected = true;
                        }
                    });
                })
                .catch(error => console.error('Error fetching cities:', error));
        }

    </script>