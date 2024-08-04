@extends('layout')

@section('page-title', '{{$user->userInfo->name}}')
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchProvinces();

        document.getElementById('provinceSelect').addEventListener('change', function() {
            var provinceCode = this.value;
            fetchCities(provinceCode);
        });
    });



    function fetchProvinces() {
        fetch('https://psgc.cloud/api/provinces')
            .then(response => response.json())
            .then(data => {
                var provinceSelect = document.getElementById('provinceSelect');
                data.sort((a, b) => a.name.localeCompare(b.name));
                data.forEach(province => {
                    var option = document.createElement('option');
                    option.value = province.name;
                    option.text = province.name;
                    provinceSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching provinces:', error));
    }

    function fetchCities(provinceCode) {
        fetch(`https://psgc.cloud/api/provinces/${provinceCode}/cities-municipalities`)
            .then(response => response.json())
            .then(data => {
                var citySelect = document.getElementById('citySelect');
                citySelect.innerHTML = '<option value="">Select City</option>';
                data.sort((a, b) => a.name.localeCompare(b.name));
                data.forEach(city => {
                    var option = document.createElement('option');
                    option.value = city.name;
                    option.text = city.name;
                    citySelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching cities:', error));
    }

    function togglePWDSection() {
        var roleSelect = document.getElementById('role');
        var pwdSection = document.getElementById('pwd-section');
        var disabilitySection = document.getElementById('disability-section');
        var disabilitySelect = document.getElementById('floatingSelect');

        if (roleSelect.value === '2') {
            pwdSection.style.display = 'block';
            disabilitySection.style.display = 'block';
            for (var i = 0; i < disabilitySelect.options.length; i++) {
                if (disabilitySelect.options[i].value === '1') {
                    disabilitySelect.remove(i);
                    break;
                }
                var optionExists = false;
            }

        } else {
            pwdSection.style.display = 'none';
            disabilitySection.style.display = 'none';
            for (var i = 0; i < disabilitySelect.options.length; i++) {
                if (disabilitySelect.options[i].value === '1') {
                    optionExists = true;
                    break;
                }
            }
            if (!optionExists) {
                var notApplicableOption = document.createElement('option');
                notApplicableOption.value = '1';
                notApplicableOption.text = 'Not Applicable';
                disabilitySelect.add(notApplicableOption);
            }

            disabilitySelect.value = '1';
        }
    }
</script>