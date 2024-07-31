@extends('layout')

@section('page-title', 'Browse Training Programs')

@section('page-content')

<div class="pwd-browse-prog">
    @include('pwd.show')
    <div class="filter-container">
        <form action="{{ route('pwd-list-program') }}" method="GET" id="filterForm">
            <div class="d-flex justify-content-between mb-3">
                <h3>Filter</h3>
                <i class='bx bx-filter-alt fs-3 sub-text'></i>
            </div>
            <div class="mb-3">
                <span>
                    <p>Disabilities</p>
                </span>
                @foreach($disabilities as $disability)
                @if($disability->disability_name !== "Not Applicable")
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{$disability->disability_name}}" id="flexCheckDefault{{$loop->index}}" name="disability[]" onchange="submitForm()" {{ in_array($disability->disability_name, request()->input('disability', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexCheckDefault{{$loop->index}}">
                        {{$disability->disability_name}}

                    </label>
                </div>
                @endif
                @endforeach
            </div>
            <div class="mb-3">
                <span>
                    <p>Education Level</p>
                </span>
                @foreach($educations as $education)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{$education->education_name}}" id="flexCheckChecked{{$loop->index}}" name="education[]" onchange="submitForm()" {{ in_array($education->education_name, request()->input('education', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexCheckChecked{{$loop->index}}">
                        {{$education->education_name}}
                    </label>
                </div>
                @endforeach
            </div>
        </form>
    </div>
    <div class="d-flex flex-column align-items-center">
        <div class="mb-4 searchbar-container">
            <div class="col d-flex justify-content-center">
                <form role="search" action="{{ route('pwd-list-program') }}" method="GET" id="searchForm">
                    <div class="d-flex searchbar">
                        <input class="form-control" type="search" placeholder="Search Training Programs" aria-label="Search" id="searchInput" onchange="checkAndSubmit()" name="search" value="{{ request('search') }}">
                        <button class="btn btn-outline-success searchButton" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="prog-grid">
            @foreach ($paginatedItems as $ranked)
            <div class="row prog-card mb-2">
                <div class="col ">
                    <a href="" class="d-flex prog-texts" data-id="{{ $ranked['program']->id }}" onclick="openPopup(event)">
                        <div class="prog-texts-container">
                            <div class=" d-flex mb-2">
                                <div class="prog-img"></div>
                                <div class="d-flex justify-content-between prog-head">
                                    <div class="header">
                                        <h4 class="text-cap">{{$ranked['program']->title}}</h4>
                                        <p class="sub-text text-cap">{{$ranked['program']->agency->userInfo->name}}</p>
                                        <p class="sub-text text-cap"><i class='bx bx-map sub-text'></i>{{(str_contains($ranked['program']->city, 'City') ? $ranked['program']->city : $ranked['program']->city . ' City')}}</p>
                                    </div>
                                    <div class="text-end date-posted">
                                        <p class="text-end">{{ $ranked['program']->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                            </div>
                            <div class="row prog-desc mb-1">
                                <p>{{$ranked['program']->description}}</p>
                            </div>
                            <div class="row d-flex">
                                <div class="match-info">
                                    {{$ranked['program']->disability->disability_name}}
                                </div>
                                <div class="match-info">
                                    {{$ranked['program']->education->education_name}}
                                </div>
                            </div>
                        </div>
                        <div class="fs-3 d-flex flex-column align-items-center justify-content-center">
                            >
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
            <div class="pagination">
                {{ $paginatedItems->links() }}
            </div>

        </div>
    </div>
</div>

@endsection

<script>
    function submitForm() {
        document.getElementById('filterForm').submit();


    }

    function checkAndSubmit() {
        var searchInput = document.getElementById('searchInput');
        if (searchInput.value.trim() === ' ') {
            document.getElementById('searchForm').submit();
        }
    }

    function openPopup(event) {
        event.preventDefault();
        var container = document.getElementById('popup');
        var programId = event.currentTarget.getAttribute('data-id');

        // Make an AJAX request to fetch the program details
        fetch(`/training-details/${programId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('title').textContent = data.title;
                document.getElementById('agency').textContent = data.agency.user_info.name;
                document.getElementById('city').textContent = data.city;
                document.getElementById('desc').textContent = data.description;
                document.getElementById('start').textContent = data.start;
                document.getElementById('end').textContent = data.end;
                document.getElementById('disability').textContent = data.disability.disability_name;
                document.getElementById('education').textContent = data.education.education_name;

                document.getElementById('apply-button').setAttribute('data-program-id', programId);
            })
            .catch(error => console.error('Error fetching program details:', error));

        // Display the popup
        container.style.display = 'flex';
    }
</script>