@extends('layout')

@section('page-title', 'Profile')
@section('page-content')
<div class="profile-container">
    <div class="profile-info">
        <div class="profile-pic">

        </div>
        <div>
            <span>{{ Auth::user()->userInfo->name }}</span>
        </div>
    </div>
</div>

@endsection