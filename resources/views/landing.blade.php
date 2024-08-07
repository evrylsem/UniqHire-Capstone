@extends('layout')

@section('page-title', 'Welcome to UniqHire')
@section('auth-content')
<style>
    .home-container {
        padding-left: 3rem;
        padding-right: 3rem;
    }

    .home-container .navbar {
        padding-left: 0;
    }

    .nav-item .submit-btn {
        padding: 0.5rem;
        width: 7rem;
        margin-right: 4rem;
    }
</style>
<div class="home-container content-container">
    <nav class="navbar border-bottom">
        <div class="navbar-container">
            <div>
                <ul class="d-flex align-items-center">
                    <li class="logo-container"><a href="#"><img class="logo" src="{{ asset('images/logo.png') }}" alt=""></a></li>
                    <li class="nav-item"><a href="{{route('landing')}}" class="{{ request()->routeIs('landing') ? 'active' : '' }}">Home</a></li>

                </ul>
            </div>
            <div>
                <ul class="d-flex align-items-center">
                    <li class="nav-item user-index">
                        <form action="{{route('login-page')}}" method="get"><button class="submit-btn border-0" type="submit">Login</button></form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
<div class="home-container">
    <div class="row home-top border-bottom">
        <div class="col-5 home-captions">
            <div class="header-caption mb-3">
                <h1 class="header-text">Find Opportunity in UniqHire</h1>
            </div>
            <div class="sub-caption mb-4">
                <p>Welcome to Uniqhire, where every ability finds opportunity! Creating bridges to people with disabilities, fostering inclusivity and celebrating diverse talents. Join us in building a world where everyone thrives!</p>
            </div>
            <div class="">
                <a href="{{route('login-page')}}" class="btn-outline">Explore</a>
            </div>
            </form>
        </div>
        <div class="col">
            <div id="carouselExample" class="carousel slide carousel-container">
                <div class="carousel-inner">
                    @foreach ( $images as $index => $image )
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset($image) }}" class="d-block w-100" alt="...">

                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
    <div class="row home-row mt-5 home-top">
        <div class="col">
            <h2 class="mb-4">Mission</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
        <div class="col-1"></div>
        <div class="col mb-4">
            <h2 class="mb-4">Vision</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
    </div>
    <div class="row d-flex align-items-center home-row mb-5 home-top border-bottom text-center count-user-row">
        <div class="col">
            <div class="d-flex flex-column justify-content-center text-center">
                <i class='bx bx-handicap count-index-icon'></i>
                <span class="user-count">
                    {{ $pwdCount }}
                    <p class="user-count">PWDs</p>
                </span>
            </div>
        </div>
        <div class="col">
            <div class="d-flex flex-column justify-content-center text-center">
                <i class='bx bxs-school count-index-icon'></i>
                <span class="user-count">
                    {{ $trainerCount }}
                    <p class="user-count">Training Agencies</p>
                </span>
            </div>
        </div>
        <div class="col">
            <div class="d-flex flex-column justify-content-center text-center">
                <i class='bx bx-briefcase-alt-2 count-index-icon'></i>
                <span class="user-count">
                    {{ $employerCount }}
                    <p class="user-count">Companies</p>
                </span>
            </div>
        </div>
        <div class="col">
            <div class="d-flex flex-column justify-content-center text-center">
                <i class='bx bx-money-withdraw count-index-icon'></i>
                <span class="user-count">
                    {{ $sponsorCount }}
                    <p class="user-count">Sponsors</p>
                </span>
            </div>
        </div>
    </div>
    <!-- <hr> -->

    <div class="row home-row mb-5 home-top border-bottom">
        <div class="col " id="about">
            <h2 class="mb-4">About Us</h2>
            <p class="mb-4">UniqHire is dedicated to creating opportunities for individuals with disabilities. We believe in a world where everyone, regardless of their abilities, can thrive and contribute to the workforce. Our platform connects talented individuals with disabilities to training programs and job opportunities tailored to their unique skills and aspirations.</p>
        </div>
    </div>

    <div class="row home-row mb-5 home-top">
        <div class="col" id="socials">
            <h2 class="mb-4">Socials</h2>
            <div class="row mb-4">
                <div class="col">
                    <div class="">
                        <span>
                            <i class='bx bxl-facebook-circle'></i> Facebook
                            <p><a href="#">facebook.com/uniqhire</a></p>
                        </span>
                    </div>
                </div>
                <div class="col">
                    <div>
                        <span>
                            <i class='bx bxl-instagram-alt'></i> Instagram
                            <p><a href="#">instagram.com/uniqhire</a></p>
                        </span>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col">
                    <div class="">
                        <span>
                            <i class='bx bxl-twitter'></i> Twitter
                            <p><a href="#">twitter.com/uniqhire</a></p>
                        </span>
                    </div>
                </div>
                <div class="col">
                    <div>
                        <span>
                            <i class='bx bxl-youtube'></i> Youtube
                            <p><a href="#">youtube.com/uniqhire</a></p>
                        </span>
                    </div>
                </div>


            </div>


        </div>
        <div class="col-1"></div>
        <div class="col">
            <form action="">
                <div class="" id="contact">
                    <h2 class="mb-4">Send us a message</h2>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" name="firsname" placeholder="First Name">
                                <label for="floatingInput">First Name</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" name="title" placeholder="Last Name">
                                <label for="floatingInput">Last Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" name="subject" required placeholder="Subject">
                                <label for="floatingInput">Subject</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" placeholder="Description" id="floatingTextarea2" name="description" style="height: 200px"></textarea>
                                <label for="floatingTextarea2">Description</label>
                                @error('description')
                                <span class="error-msg">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <!-- <div class=""> -->
                        <button type="submit" class="border-0 submit-btn">Send</button>
                        <!-- </div> -->

                    </div>
                </div>

            </form>
        </div>
    </div>
</div>



@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    });
</script>