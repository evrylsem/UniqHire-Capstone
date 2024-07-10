@extends('layout')

@section('page-title', 'Home page')

@section('page-content')
<div id="container">  
    <div id="sidebar">

        <div id="filterContainer">

            <h5 id="filter">FILTER</h5>

            <!-- <hr> -->

            <h6 id="filterLabel">DISABILITY</h6>
            
            @foreach($disabilities as $disability)
                @if($disability->disability_name !== "Not Applicable")
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">                    
                            {{$disability->disability_name}}
                            
                        </label>
                    </div>
                @endif
            @endforeach            

            <h6 id="filterLabel">EDUCATIONAL LEVEL</h6>

            @foreach($educations as $education)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                <label class="form-check-label" for="flexCheckChecked">
                    {{$education->education_name}}
                </label>
            </div>
            @endforeach
        </div>

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
        @foreach ($programs as $program)
            <div id="main-container" >
                <img src="https://i.pinimg.com/736x/ea/95/85/ea95858650d7cc828048c499969d5a74.jpg" alt="Image" id="training_image">

                <div id="main-content">
                    <h3>{{ $program->title }}</h3>
                    <h6 class="main-author">{{$program->agency->userInfo->firstname . " " . $program->agency->userInfo->lastname}}</h6>
                    <p class="training-description">{{$program->description}}</p>

                    <div id="main-requirement">
                        <h6>{{ $program->disability->disability_name }}</h6>
                        <h6>{{ $program->education->education_name }}</h6>
                    </div>

                </div>

                <p id="main-date">{{$program->created_at}}</p>

                <div id="main-icon">
                    <i class='bx bx-bookmark bx-md side-icon' style="color: #04B000"></i>
                    <a href="#"><i class='bx bx-chevron-right bx-md side-icon' style="color: #758694"></i></a>
                </div>

            </div>
        @endforeach
    </main>

    
</div>
@endsection
