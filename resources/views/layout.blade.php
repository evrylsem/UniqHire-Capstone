    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>UniqHire | @yield('page-title')</title>

        <!-- Bootstrap library -->
        <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-H7c5xz2/Bo9F3OeY8QhMDCz1p6wD5wF2gskM8H/o6cc1xVxrmT4eZ1QyD/0G0F9E" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-6C0P9i8FAEEG7T46Yc4J9E5DPur2Tz8tQ1PUgVJ7X1EoO9dJQy5Qig0P8Jk7+KD6" crossorigin="anonymous"></script> -->
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->



        @include('slugs.links')

        <!-- Boxicons library -->
        <!-- <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> -->

        <link rel="icon" href="{{ asset('images/tab-icon.png') }}">

    </head>

    <body>
        @if (session('success'))
        <script>
            Swal.fire({
                title: "Success",
                text: "{{session('success')}}",
                icon: "success",
                timer: 3000,
            });
        </script>
        @endif
        @if (session('error'))
        <script>
            Swal.fire({
                title: "Error",
                text: "{{session('error')}}",
                icon: "error",
                timer: 3000,
            });
        </script>
        @endif
        <div class="layout-container">
            @if (Auth::check())
            <nav class="sidebar">
                <header class="">
                    <div class="logo-sidebar">
                        <span class="logo-img">
                            <!-- <img src="{{ asset('images/tab-icon.png')}} " alt=""> -->
                            <i class='bx bx-menu side-icon'></i>
                        </span>
                        <div class="logo-name">
                        </div>
                    </div>
                </header>
                <div class="sidebar-menu">
                    <ul class="">
                        <li class="side-item">
                            <a href="{{ route('profile')}}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                                <i class='bx bx-user-circle side-icon'></i>
                                <span class="side-title">Profile</span>
                            </a>
                        </li>

                        <!-- PWD ROLE ACCESS -->
                        @if (Auth::user()->hasRole('PWD'))
                        <li class="side-item">
                            <a href="{{route('trainings')}}" class="trainings-drop {{ request()->routeIs('trainings', 'show-details') ? 'active' : '' }}">
                                <i class='bx bxs-school side-icon'></i>
                                <span class="side-title">Trainings</span>
                            </a>
                        </li>
                        <li class="side-item">
                            <a href="{{ route('pwd-calendar') }}" class="{{ request()->routeIs('pwd-calendar') ? 'active' : '' }}">
                                <i class='bx bx-calendar side-icon'></i>
                                <span class="side-title">Calendar</span>
                            </a>
                        </li>
                        <li class="side-item">
                            <a href="#">
                                <i class='bx bx-cog side-icon'></i>
                                <span class="side-title">Settings</span>
                            </a>
                        </li>

                        @endif

                        <!-- ADMIN ROLE ACCESS -->
                        @if (Auth::user()->hasRole('Admin'))
                        <li class="side-item"><a href="{{route('pwd-list')}}">
                                <i class='bx bx-handicap side-icon'></i>
                                <span class="side-title">PWDs</span>
                            </a></li>
                        <li class="side-item"><a href="{{route('trainer-list')}}">
                                <i class='bx bxs-school side-icon'></i>
                                <span class="side-title">Training Agencies</span>
                            </a></li>
                        <li class="side-item"><a href="{{route('employee-list')}}">
                                <i class='bx bx-briefcase-alt-2 side-icon'></i>
                                <span class="side-title">Employers</span>
                            </a></li>
                        <li class="side-item"><a href="{{route('sponsor-list')}}">
                                <i class='bx bx-dollar-circle side-icon'></i>
                                <span class="side-title">Sponsors</span>
                            </a></li>
                        <li class="side-item"><a href="">
                                <i class='bx bx-cog side-icon'></i>
                                <span class="side-title">Settings</span>
                            </a></li>
                        <!-- <li><a href="#"><i class='bx bx-briefcase-alt-2 side-icon'></i><span class="side-title">Employers</span></a></li> -->
                        @endif
                        <!-- TRAINER ROLE ACCESS -->
                        @if (Auth::user()->hasRole('Training Agency'))
                        <li class="side-item">
                            <a href="{{route('programs-manage')}}" class="{{ request()->routeIs('programs-manage', 'programs-add', 'programs-edit', 'programs-show') ? 'active' : '' }}">
                                <i class='bx bxs-school side-icon'></i>
                                <span class="side-title">Training Programs</span>
                            </a>
                        </li>
                        <li class="side-item">
                            <a href="{{ route('agency-calendar') }}" class="{{ request()->routeIs('agency-calendar') ? 'active' : '' }}">
                                <i class='bx bx-calendar side-icon'></i>
                                <span class="side-title">Calendar</span>
                            </a>
                        </li>
                        @endif

                        <!-- <li><a href="#"><i class='bx bx-cog side-icon'></i><span class="side-title">Sponsor</span></a></li> -->
                    </ul>
                    <div class="sidebar-bottom">
                        <li class=""><a href="{{ url('/logout')}}"><i class='bx bx-log-out-circle side-icon'></i><span class="side-title">Logout</span></a></li>
                    </div>
                </div>
            </nav>
            <div class="">
                <div class=" content-container">
                    <nav class="navbar border-bottom">
                        <div class="navbar-container">
                            <div>
                                <ul class="d-flex align-items-center">
                                    <li class="logo-container"><a href="#"><img class="logo" src="{{ asset('images/logo.png') }}" alt=""></a></li>
                                    <li class="nav-item"><a href="{{route('home')}}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                                    @if (Auth::user()->hasRole('PWD'))
                                    <li class="nav-item"><a href="{{route('pwd-list-program')}}" class="{{ request()->routeIs('pwd-list-program', 'programs-show', 'training-details') ? 'active' : '' }}">Browse Training Programs</a></li>
                                    <li class="nav-item"><a href="">Find Work</a></li>
                                    @endif

                                    <!-- <li class="nav-item"><a href="{{ route('home') }}/#about" class="">About</a></li> -->

                                </ul>
                            </div>
                            <div>
                                <ul class="d-flex align-items-center">
                                    <li class="nav-item user-notif dropdown">

                                        <a href="#" class="dropdown-toggle" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class='bx bxs-inbox'></i>
                                            <span id="notification-badge" class="badge bg-danger d-none">0</span> <!-- Badge element -->
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                                            <!-- Notifications will be dynamically added here -->
                                        </ul>
                                    </li>

                                    <li class="nav-item user-index"><span>{{ Auth::user()->userInfo->name }}</span></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="content-container">
                    @yield('page-content')
                </div>
            </div>
            @else
            <div class="container-fluid">
                @yield('auth-content')
            </div>
            @endif
        </div>
    </body>
    <script>
        $(document).ready(function() {
            function fetchNotifications() {
                $.get("{{ route('notifications.getNotifications') }}", function(data) {
                    console.log(data);
                    var notifDropdown = $('#notificationDropdown').next('.dropdown-menu');
                    var badge = $('#notification-badge');
                    notifDropdown.empty(); // Clear existing notifications

                    if (data.length > 0) {
                        badge.removeClass('d-none').text(data.length);
                        data.forEach(function(notification) {
                            var notificationContent = '';
                            if (notification.type === 'App\\Notifications\\NewTrainingProgramNotification') {
                                notificationContent = '<li><a class="dropdown-item" href="' + notification.data.url + '">' +
                                    '<span class="notif-owner text-cap">' +
                                    notification.data.agency_name +
                                    '</span>' +
                                    ' has posted a new training' +
                                    '<div class="notif-content sub-text">' +
                                    'Entitled ' +
                                    '<span class="sub-text text-cap">' +
                                    notification.data.title +
                                    '</span>' +
                                    '. Starts on ' +
                                    '<span class="sub-text">' +
                                    notification.data.start_date + //Change Format pero if dili makaya kay ayaw nlng sya iapil og display
                                    '</span>' +
                                    '. Click to check this out.' +
                                    '</div>' +
                                    '</a></li>'
                            } else if (notification.type === 'App\\Notifications\\PwdApplicationNotification') {
                                notificationContent = '<li><a class="dropdown-item" href="' + notification.data.url + '">' +
                                    'A PWD user has applied for your training program: ' +
                                    '<span class="sub-text text-cap">' +
                                    notification.data.title +
                                    '</span>' +
                                    '. Click to view application.' +
                                    '</a></li>';
                            } else if (notification.type === 'App\\Notifications\\ApplicationAcceptedNotification') {
                                notificationContent = '<li><a class="dropdown-item" href="' + notification.data.url + '">' +
                                    'Your application in ' +
                                    '<span class="notif-owner text-cap">' +
                                    notification.data.program_title +
                                    '</span>' +
                                    '<div class="notif-content sub-text">' +
                                    ' has been accepted by'
                                '<span class="notif-owner text-cap">' +
                                notification.data.agency_name +
                                    '</span>' +
                                    '. Click to view details.' +
                                    '</div>'
                                '</a></li>';
                            }
                            notifDropdown.append(notificationContent);
                        });
                    } else {
                        badge.addClass('d-none');
                        notifDropdown.append('<li><span class="dropdown-item">No notifications</span></li>');
                    }
                }).fail(function() {
                    console.error('Failed to fetch notifications');
                });
            }

            // Fetch notifications on page load
            fetchNotifications();

            // Handle dropdown showing
            $('#notificationDropdown').on('show.bs.dropdown', function() {
                fetchNotifications();
            });
        });

        function toggleSubmenu() {
            var submenu = document.getElementById('trainings-submenu');
            var icon = document.getElementById('arrow-drop');

            submenu.classList.toggle('active-drop');
            icon.classList.toggle('arrow-down');
        }
    </script>

    </html>