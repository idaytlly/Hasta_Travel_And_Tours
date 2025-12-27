@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Edit Profile</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
        </div>

        <!-- Only for customer -->
        @if($user->usertype === 'customer' || $user->usertype === 'user')
            <div class="mb-3">
                <label>IC</label>
                <input type="text" name="ic" class="form-control" value="{{ $user->ic }}">
            </div>
            <div class="mb-3">
                <label>Street</label>
                <input type="text" name="street" class="form-control" value="{{ $user->street }}">
            </div>
            <div class="mb-3">
                <label>City</label>
                <input type="text" name="city" class="form-control" value="{{ $user->city }}">
            </div>
            <div class="mb-3">
                <label>State</label>
                <input type="text" name="state" class="form-control" value="{{ $user->state }}">
            </div>
            <div class="mb-3">
                <label>Postcode</label>
                <input type="text" name="postcode" class="form-control" value="{{ $user->postcode }}">
            </div>
            <div class="mb-3">
                <label>Driver License</label>
                <input type="text" name="license_no" class="form-control" value="{{ $user->license_no }}">
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
@endsection
