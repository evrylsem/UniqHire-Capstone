@extends('layouts.app')

@section('training-details')
<div class="details-container" id="details-container">
    <div class="header border-bottom d-flex align-items-center justify-content-between mb-2">
        <a href="#" id="close-popup"><i class='bx bx-x'></i></a>
        <h4>TRAINING DETAILS</h4>
        <div class="empty-space"></div>
    </div>
    <div class="body">
        <div class="row mb-4">
            <div class="col">
                <h3 id="title">{{$program->title}}</h3>
                <p class="sub-text" id="agency">by agency name</p>
                <p class="sub-text" id="city"><i class='bx bx-map sub-text'></i> City</p>
            </div>
            <div class="col text-end prog-btn">
                <form action="" method="POST">
                    <button class="submit-btn border-0">Apply</button>
                </form>
            </div>
        </div>

        <div class="mb-5">
            <p id="desc">Description</p>
        </div>
        <div class="row more-info">
            <div class="col">
                <h5>Start Date</h5>
                <p id="start">Start</p>
            </div>
            <div class="col">
                <h5>End Date</h5>
                <p id="end">End</p>
            </div>
        </div>
        <div class="row more-info">
            <div class="col">
                <h5>We Accept</h5>
                <span class="requirement" id="disability">Disability</span>
            </div>
            <div class="col">
                <h5>Education Level</h5>
                <span class="requirement" id="education">Education</span>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var closeBtn = document.getElementById('close-popup');
        var container = document.getElementById('details-container');

        function togglePopup() {
            if (container.style.display === 'none' || container.style.display === '') {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        }

        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            container.style.display = 'none';
        });

        function openPopup() {
            container.style.display = 'block';
        }
    });
</script>