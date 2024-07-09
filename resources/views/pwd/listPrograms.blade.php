@extends('layout')

@section('page-title', 'Home page')

@section('page-content')
<div id="container">  
    <div id="sidebar">

        <div id="filterContainer">

            <h5 id="filter">FILTER</h5>

            <!-- <hr> -->

            <h6 id="filterLabel">DISABILITY</h6>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Arm Amputee
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                <label class="form-check-label" for="flexCheckChecked">
                    Leg Amputee
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                <label class="form-check-label" for="flexCheckChecked">
                    Hearing Impaired
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                <label class="form-check-label" for="flexCheckChecked">
                    Speech Impairment
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                <label class="form-check-label" for="flexCheckChecked">
                    Visually Impaired
                </label>
            </div>

            <!-- <hr> -->

            <h6 id="filterLabel">EDUCATIONAL LEVEL</h6>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                <label class="form-check-label" for="flexCheckChecked">
                    High School Level
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                <label class="form-check-label" for="flexCheckChecked">
                    Some College
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                <label class="form-check-label" for="flexCheckChecked">
                    Bachelor's Degree
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                <label class="form-check-label" for="flexCheckChecked">
                    Vocational
                </label>
            </div>

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
        <div id="main-column">

            <div id="main-container" >
                <img src="https://i.pinimg.com/736x/ea/95/85/ea95858650d7cc828048c499969d5a74.jpg" alt="Image" id="training_image">

                <div id="main-content">
                    <h3>Text Ni</h3>
                    <h6 class="main-author">gamay na text</h6>
                    <p class="training-description">This is a very long description that will wrap to the next line if it overflows the container's width.</p>

                    <div id="main-requirement">
                        <h6>Leg Amputee</h6>
                        <h6>High School Level</h6>
                    </div>

                </div>

                <p id="main-date">date</p>

                <div id="main-icon">
                    <i class='bx bx-bookmark bx-md side-icon' style="color: #04B000"></i>
                    <a href="#"><i class='bx bx-chevron-right bx-md side-icon' style="color: #758694"></i></a>
                </div>

            </div>

            <hr class="main-hr">

        </div>
    </main>

    
</div>
@endsection
