@extends('layout')
@section('page-title', 'Add Training Program')
@section('page-content')
<form action="{{ route('programs-add') }}" method="POST" class="container mb-5 add-form">
    @csrf
    <div class="row mt-2 mb-2 border-bottom">
        <div class="text-start header-texts back-link-container">
            <a href="{{ route('programs-manage') }}" class="m-1 back-link"><i class='bx bx-left-arrow-alt'></i></a>
            Add Training Program.
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" name="title" value="{{old('title')}}" required placeholder="First Name">
                <label for="floatingInput">Title</label>
                @error('title')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-floating mb-3">
                <select type="text" class="form-select" id="provinceSelect" name="state" required placeholder="Province">
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
                <select type="text" class="form-select" id="citySelect" name="city" required placeholder="City">
                    <option value="">Select City</option>
                </select>
                <label for="citySelect">City</label>
                @error('city')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-floating mb-3">
                <textarea class="form-control" placeholder="Description" id="floatingTextarea2" name="description" style="height: 200px">{{old('description')}}</textarea>
                <label for="floatingTextarea2">Description</label>
                @error('description')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-floating mb-3">
                <input type="number" class="form-control" id="startAge" name="start_age" value="{{old('start_age')}}" required placeholder="Input Age">
                <label for="floatingInput">Start Age</label>
                @error('age')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col">
            <div class="form-floating mb-3">
                <input type="number" class="form-control" id="endAge" name="end_age" value="{{old('end_age')}}" required placeholder="Input Age">
                <label for="floatingInput">End Age</label>
                @error('age')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="participants" name="participants" value="{{old('participants')}}" required placeholder="Input Participants" oninput="formatNumber(this)">
                <label for="floatingInput">Number of Participants</label>
                @error('participants')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col">
            <div class="form-floating mb-3">
                <select class="form-select" id="floatingSelect" name="skills" aria-label="Floating label select example">
                    @foreach ($skills as $skill)
                    <option value="{{ $skill->id }}">{{ $skill->title }}</option>
                    @endforeach
                </select>
                <label for="floatingSelect">Select Skill</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="mb-3">
                <label for="">Start Date: </label>
                <input type="date" name="start_date" class="date-input">
            </div>
        </div>
        <div class="col">
            <div class="mb-3">
                <label for="">End Date: </label>
                <input type="date" name="end_date" class="date-input">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-floating mb-3">
                <select class="form-select" id="floatingSelect" name="disability" aria-label="Floating label select example">
                    @foreach ($disabilities as $disability)
                    @if ($disability->disability_name != 'Not Applicable')
                    <option value="{{ $disability->id }}">{{ $disability->disability_name }}</option>
                    @endif
                    @endforeach

                </select>
                <label for="floatingSelect">Disability</label>
            </div>
        </div>
        <div class="col">
            <div class="form-floating mb-3">
                <select class="form-select" id="floatingSelect" name="education" aria-label="Floating label select example">
                    @foreach ($levels as $level)
                    @if ($level->education_name != 'Not Applicable')
                    <option value="{{ $level->id }}">{{ $level->education_name }}</option>
                    @endif
                    @endforeach

                </select>
                <label for="floatingSelect">Education Level</label>
            </div>
        </div>
    </div>
    <hr>
    <div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="host-crowdfund" onchange="toggleCrowdfund()">
            <label class="form-check-label" for="flexCheckDefault">
                Host a crowdfunding for this?
            </label>
        </div>
    </div>
    <div class="row" id="crowdfund-section">
        <div class="col">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="amount-needed" name="goal" required placeholder="Amount Needed" disabled oninput="formatNumber(this)">
                <label for="floatingInput">Amount Needed</label>
                @error('goal')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <!-- COMPETENCY -->
    <div class="row border-bottom">
        <div class="col">
            <div class="form-floating mb-3">
                <div id="competencyListContainer">
                    <label for="competencyList">Competencies:</label>
                    <div id="competencyList"></div>
                    <button type="button" id="addCompetencyBtn" class="submit-btn border-0 add-comp"><i class="bx bx-plus"></i> Add Competency</button>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-evenly mt-3 prog-btn">
        <button type="reset" class="deny-btn border-0">Clear</button>
        <button type="submit" class="submit-btn border-0">Add</button>
    </div>
</form>


@endsection

<script>
    function formatNumber(input) {
        let value = input.value.replace(/,/g, '');
        if (!isNaN(value) && value !== '') {
            input.value = Number(value).toLocaleString();
        }
    }

    function toggleCrowdfund() {
        var hostCrowdfund = document.getElementById('host-crowdfund');
        var crowdfundSection = document.getElementById('crowdfund-section');

        if (hostCrowdfund.checked) {
            // crowdfundSection.style.display = 'block';
            document.getElementById('amount-needed').disabled = false;
            document.getElementById('amount-needed').required = true;
        } else {
            // crowdfundSection.style.display = 'none';
            document.getElementById('amount-needed').disabled = true;
            document.getElementById('amount-needed').required = false;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchProvinces();

        document.getElementById('provinceSelect').addEventListener('change', function() {
            var provinceCode = this.value;
            fetchCities(provinceCode);
        });

        let competencyCount = 0;
        const addCompetencyBtn = document.getElementById('addCompetencyBtn');
        const competencyList = document.getElementById('competencyList');

        function toggleButtons() {
            if (competencyCount >= 4) {
                addCompetencyBtn.classList.add('d-none');
            } else {
                addCompetencyBtn.classList.remove('d-none');
            }
        }

        addCompetencyBtn.addEventListener('click', function() {
            if (competencyCount < 4) {
                competencyCount++;
                const competencyItem = document.createElement('div');
                competencyItem.className = 'input-group mb-3';
                competencyItem.innerHTML = `
                <input type="text" class="form-control" placeholder="Enter competency" name="competencies[]" required>
                <button class="btn btn-outline-secondary remove-btn" type="button">Remove</button>
            `;
                competencyList.appendChild(competencyItem);

                competencyItem.querySelector('.remove-btn').addEventListener('click', function() {
                    competencyList.removeChild(competencyItem);
                    competencyCount--;
                    toggleButtons();
                });

                competencyItem.querySelector('input').addEventListener('input', toggleButtons);

                toggleButtons();
            }
        });

        toggleButtons(); // Initialize the button states
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
</script>