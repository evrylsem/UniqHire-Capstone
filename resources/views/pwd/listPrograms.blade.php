@extends('layout')

@section('page-title', 'Browse Training Programs')

@section('page-content')

<div class="pwd-browse-prog">
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
                @if($disability->id !== 1)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{$disability->disability_name}}" id="flexCheckDefault{{$loop->index}}" name="disability[]" onchange="submitForm()" {{ in_array($disability->disability_name, request()->input('disability', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexCheckDefault{{$loop->index}}">
                        {{$disability->disability_name}} &nbsp;<span class="count sub-text">({{ $disabilityCounts[$disability->id]->program_count }})</span>

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
                @if($education->id !== 1)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{$education->education_name}}" id="flexCheckChecked{{$loop->index}}" name="education[]" onchange="submitForm()" {{ in_array($education->education_name, request()->input('education', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="flexCheckChecked{{$loop->index}}">
                        {{$education->education_name}} &nbsp;<span class="count sub-text">({{ $educationCounts[$education->id]->program_count }})</span>
                    </label>
                </div>
                @endif
                @endforeach
            </div>
        </form>
    </div>
    <div class="d-flex flex-column align-items-center browse-area">
        <div class="mb-4 searchbar-container">
            <div class="col d-flex justify-content-center">
                <form role="search" action="{{ route('pwd-list-program') }}" method="GET" id="searchForm">
                    <div class="d-flex searchbar">
                        <input class="form-control" type="search" placeholder="Search Training Programs" aria-label="Search" id="searchInput" onchange="checkAndSubmit()" name="search" value="{{ request('search') }}">
                        <button class="submit-btn border-0" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="outer">
            <div class="prog-grid" id="prog-grid">
                @foreach ($paginatedItems as $ranked)
                <div class="row prog-card mb-2">
                    <input type="hidden" name="" value="{{$ranked['similarity']}}" id="">
                    <div class="col ">
                        <a href="{{ route('training-details', $ranked['program']->id ) }}" class="d-flex prog-texts">
                            <div class="prog-texts-container">
                                <div class=" d-flex mb-2">
                                    <div class="prog-img" @if (!empty($ranked['program']->agency->userInfo->profile_path)) style=" background-image: url({{ asset($ranked['program']->agency->userInfo->profile_path) }}); background-repeat: no-repeat; background-size: cover; " @endif>

                                        @if (empty($ranked['program']->agency->userInfo->profile_path))
                                        <span>{{ strtoupper(substr($ranked['program']->agency->userInfo->name, 0, 1)) }}</span>
                                        @endif

                                    </div>
                                    <div class="d-flex justify-content-between prog-head">
                                        <div class="header">
                                            <h4 class="text-cap">{{$ranked['program']->title}}</h4>
                                            <p class="sub-text text-cap">{{$ranked['program']->agency->userInfo->name}}</p>
                                            <p class="sub-text text-cap"><i class='bx bx-map sub-text'></i>{{$ranked['program']->state . ', ' .(str_contains($ranked['program']->city, 'City') ? $ranked['program']->city : $ranked['program']->city . ' City')}}</p>
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
                                    <div class="match-info @if (Auth::user()->userInfo->disability->id != $ranked['program']->disability->id) notmatch-info @endif ">
                                        {{$ranked['program']->disability->disability_name}}
                                    </div>
                                    <div class="match-info @if (Auth::user()->userInfo->education->id != $ranked['program']->education->id) notmatch-info @endif">
                                        {{$ranked['program']->education->education_name}}
                                    </div>
                                    <div class="match-info @if (Auth::user()->userInfo->age < $ranked['program']->start_age || Auth::user()->userInfo->age > $ranked['program']->end_age) notmatch-info @endif">
                                        {{$ranked['program']->start_age . ' - ' . $ranked['program']->end_age}}
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
</div>

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
</script>
@endsection