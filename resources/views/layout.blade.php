<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>UniqHire | @yield('page-title')</title>

    <!-- Bootstrap library -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Fullcalendar library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    
    @include('slugs.links')

    <!-- Boxicons library -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="icon" href="{{ asset('images/tab-icon.png') }}">

</head>

<body>
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
                        <!-- <span>UniqHire</span> -->
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
                        <a href="#" class="trainings-drop" onclick="toggleSubmenu();">
                            <i class='bx bxs-school side-icon'></i>
                            <span class="side-title">Trainings &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i id="arrow-drop" class='bx bxs-right-arrow'></i></span>
                        </a>
                    </li>
                    <div class="submenu" id="trainings-submenu">
                        <li>
                            <a href="">
                                <i class='bx bx-timer'></i>
                                <span class="side-title">On-going</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <i class='bx bx-calendar-check'></i>
                                <span class="side-title">Completed</span>
                            </a>
                        </li>
                    </div>
                    <li class="side-item">
                        <a href="#">
                            <i class='bx bx-briefcase-alt-2 side-icon'></i>
                            <span class="side-title">Job Application</span>
                        </a>
                    </li>
                    <li class="side-item">
                        <a href="{{ route('pwd-calendar') }}">
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
                    @if (Auth::user()->hasRole('Trainer'))
                    <li class="side-item">
                        <a href="{{route('programs-manage')}}">
                            <i class='bx bxs-school side-icon'></i>
                            <span class="side-title">Training Programs</span>
                        </a>
                    </li>
                    <li class="side-item">
                        <a href="{{ route('agency-calendar') }}">
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
                                <li class="nav-item"><a href="{{route('pwd-list-program')}}" class="{{ request()->routeIs('pwd-list-program') ? 'active' : '' }}">Browse Training Programs</a></li>
                                <li class="nav-item"><a href="">Find Work</a></li>
                                @endif

                                <!-- <li class="nav-item"><a href="{{ route('home') }}/#about" class="">About</a></li> -->

                            </ul>
                        </div>
                        <div>
                            <ul class="d-flex align-items-center">
                                <li class="nav-item user-notif"><a href="#"><i class='bx bxs-inbox'></i></a></li>
                                <li class="nav-item user-index"><span>{{ Auth::user()->userInfo->name }}</span></li>
                            </ul>
                        </div>

                    </div>
                </nav>
            </div>
            <div class="content-container">
                @yield('calendar')
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notifDropdown = document.getElementById('notifDropdown');
            const notificationsMenu = document.getElementById('notificationsMenu');
            const badge = document.querySelector('.badge');

            function fetchNotifications() {
                fetch("{{ route('notifications') }}")
                    .then(response => response.json())
                    .then(data => {
                        notificationsMenu.innerHTML = '';
                        let unreadCount = 0;

                        if (data.length > 0) {
                            data.forEach(notif => {
                                if (!notif.read_at) {
                                    unreadCount++;
                                }
                                notificationsMenu.innerHTML += `
                                <li class="${notif.read_at ? 'read' : 'unread'}">
                                    <a href="{{ url('/training-programs') }}/${notif.data.training_program_id}" data-id="${notif.id}" class="notif-item">
                                        ${notif.data.title || 'No title'}
                                    </a>
                                </li>
                            `;
                            });
                        } else {
                            notificationsMenu.innerHTML = '<li>No new notifications</li>';
                        }

                        badge.textContent = unreadCount > 0 ? unreadCount : '';
                    });
            }

            function markNotificationAsRead(id) {
                fetch("{{ route('notifications.markAsRead') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        notification_id: id
                    })
                }).then(response => {
                    if (response.ok) {
                        fetchNotifications(); // Refresh notifications
                    }
                });
            }

            notifDropdown.addEventListener('click', function(e) {
                e.preventDefault();
                fetchNotifications();
            });

            notificationsMenu.addEventListener('click', function(e) {
                if (e.target.classList.contains('notif-item')) {
                    const notifId = e.target.getAttribute('data-id');
                    markNotificationAsRead(notifId);
                }
            });

            // Fetch notifications on page load
            fetchNotifications();
        });
    </script>

    @yield('scripts')




</body>
<script>
    function toggleSubmenu() {
        var submenu = document.getElementById('trainings-submenu');
        var icon = document.getElementById('arrow-drop');

        submenu.classList.toggle('active-drop');
        icon.classList.toggle('arrow-down');
    }
</script>

</html>