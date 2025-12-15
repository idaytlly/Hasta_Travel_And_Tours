<x-app-layout>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Account Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                <h3 class="text-2xl font-bold mb-4">Welcome Back!</h3>
                
                <p class="mb-6">
                    Hello, **{{ Auth::user()->name }}**. You are successfully logged in and viewing your account dashboard.
                </p>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">Recent Bookings</div>
                            <div class="card-body">
                                <p class="card-text">Check the status of your last three car rentals.</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">View All Bookings</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card border-secondary">
                            <div class="card-header bg-secondary text-white">Account Settings</div>
                            <div class="card-body">
                                <p class="card-text">Need to update your phone number or password?</p>
                                <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-secondary">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
</x-app-layout>