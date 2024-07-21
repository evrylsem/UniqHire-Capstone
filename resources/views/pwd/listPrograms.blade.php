@extends('layout')

@section('page-title', 'Browse Training Programs')

@section('page-content')

<div class="row mt-2 pwd-browse-prog">
    <div class="filter-container">
        <form action="">
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
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
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
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                    <label class="form-check-label" for="flexCheckChecked">
                        {{$education->education_name}}
                    </label>
                </div>
                @endforeach
            </div>
        </form>
    </div>
    <div class="col empty-space"></div>
    <div class="col d-flex flex-column align-items-center">
        <div class="row mb-4">
            <div class="col d-flex justify-content-center">
                <form action="">
                    <div class="d-flex searchbar">
                        <input class="form-control" type="search" placeholder="Search Training Agency" aria-label="Search">
                        <button class="btn btn-outline-success searchButton" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="prog-grid">
            @foreach ($programs as $program)
            <div class="row prog-card mb-2">
                <!-- <div class="col-2 border prog-img">
                    <div>

                    </div>
                </div> -->
                <div class="col ">
                    <a href="" class="d-flex prog-texts">
                        <div class="prog-texts-container">

                            <div class=" d-flex mb-2">
                                <div class="prog-img"></div>
                                <div class="header">
                                    <h4>{{$program->title}}</h4>
                                    <p class="sub-text">{{$program->agency->userInfo->name}}</p>
                                    <p class="sub-text">{{$program->city}}</p>
                                </div>
                            </div>
                            <div class="row prog-desc mb-1">
                                <p>{{$program->description}}</p>
                            </div>
                            <div class="row match-info">
                                {{$program->disability->disability_name}}
                            </div>


                        </div>

                        <div class="fs-3 d-flex flex-column align-items-center justify-content-center">
                            >
                        </div>

                    </a>

                </div>

            </div>
            @endforeach
        </div>





    </div>
    <div class="col empty-space"></div>
</div>
@endsection