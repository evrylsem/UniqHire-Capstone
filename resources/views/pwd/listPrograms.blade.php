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
            <form class="d-flex justify-content-center" role="search">
                <input class="form-control searchField" type="search" placeholder="Search Training Agency" aria-label="Search">
                <button class="btn btn-outline-success searchButton" type="submit">Search</button>
            </form>
        </div>     

    </nav>

    <main id="main">

        @if(!$rankedPrograms)
            <p>No programs found.</p>
        @else        
            @foreach ($rankedPrograms as $ranked)                
                <h3>{{ $ranked['program']->title }}</h3>
                <h6 class="main-author">{{ $ranked['program']->agency_id }}</h6>             
            @endforeach        
        @endif

    </main>

</div>

@endsection

<script>
    function submitForm() {
        document.getElementById('filterForm').submit();
    }
</script>