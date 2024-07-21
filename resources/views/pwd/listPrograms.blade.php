@extends('layout')

@section('page-title', 'Home page')

@section('page-content')
<div id="container">  

    <div id="sidebar">
        <form id="filterForm" action="{{ route('pwd-list-program') }}" method="GET">
            <div id="filterContainer">

                <h5 id="filter">FILTER</h5>

                <h6 id="filterLabel">DISABILITY</h6>
                
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

                <h6 id="filterLabel">EDUCATIONAL LEVEL</h6>

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

    <nav id="nav">

        <div class="container mt-3">
            <form id="searchForm" class="d-flex justify-content-center" role="search" action="{{ route('pwd-list-program') }}" method="GET">
                <input id="searchInput" class="form-control searchField" type="search" placeholder="Search Training Agency" aria-label="Search" onchange="checkAndSubmit()" name="search" value="{{ request('search') }}">
                <button class="btn btn-outline-success searchButton" type="submit">Search</button>
            </form>
        </div>     

    </nav>

    <main id="main">

        @if($filteredPrograms != null)

            @foreach ($filteredPrograms as $filtered)
                <div id="main-container" >

                    <img src="https://i.pinimg.com/736x/ea/95/85/ea95858650d7cc828048c499969d5a74.jpg" alt="Image" id="training_image">

                    <div id="main-content">

                        <h3>{{ $filtered->title }}</h3>
                        <h6 class="main-author">{{$filtered->agency->userInfo->firstname . " " . $filtered->agency->userInfo->lastname}}</h6>
                        <p class="training-description">{{$filtered->description}}</p>

                        <div id="main-requirement">
                            <h6>{{ $filtered->disability->disability_name }}</h6>
                            <h6>{{ $filtered->education->education_name }}</h6>
                        </div>

                    </div>

                    <p id="main-date">{{$filtered->created_at}}</p>

                    <div id="main-icon">
                        <i class='bx bx-bookmark bx-md side-icon' style="color: #04B000"></i>
                        <a href="#"><i class='bx bx-chevron-right bx-md side-icon' style="color: #758694"></i></a>
                    </div>

                </div>                
            @endforeach 

        @else     

            @foreach ($rankedPrograms as $ranked)                                                
                <div id="main-container">

                    <img src="https://i.pinimg.com/736x/ea/95/85/ea95858650d7cc828048c499969d5a74.jpg" alt="Image" id="training_image">

                    <div id="main-content">

                        <h3>{{ $ranked['program']->title }}</h3>
                        <h6 class="main-author">{{ $ranked['program']->agency->userInfo->firstname . " " . $ranked['program']->agency->userInfo->lastname }}</h6>
                        <p class="training-description">{{ $ranked['program']->description }}</p>

                        <div id="main-requirement">
                            <h6>{{ $ranked['program']->disability->disability_name }}</h6>
                            <h6>{{ $ranked['program']->education->education_name }}</h6>
                        </div>

                    </div>

                    <p id="main-date">{{ $ranked['program']->created_at }}</p>

                    <div id="main-icon">
                        <i class='bx bx-bookmark bx-md side-icon' style="color: #04B000"></i>
                        <a href="#"><i class='bx bx-chevron-right bx-md side-icon' style="color: #758694"></i></a>
                    </div>

                </div>
            @endforeach        
        @endif

    </main>

</div>

@endsection

<script>

    function submitForm() {
        document.getElementById('filterForm').submit();
    }

    // Function to check if the input is empty and submit the form
    function checkAndSubmit() {
        var searchInput = document.getElementById('searchInput');
        if (searchInput.value.trim() === ' ') {
            document.getElementById('searchForm').submit();
        }
    }

    

</script>