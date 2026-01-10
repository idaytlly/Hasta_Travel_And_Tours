@extends('staff.layouts.staff')

@section('title', 'Customer Management')
@section('page-title', 'Customer Management')
@section('page-subtitle', 'Manage all customers')

@section('content')
<div class="row mb-4">
    <!-- Statistics Cards -->
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">{{ $stats['total'] }}</h5>
                <p class="card-text text-muted">Total Customers</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success">{{ $stats['active'] }}</h5>
                <p class="card-text text-muted">Active</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-danger">{{ $stats['inactive'] }}</h5>
                <p class="card-text text-muted">Inactive</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Customer List</h5>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('staff.customers.index') }}" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" 
                           placeholder="Search customers..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>License No</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>
                            <strong>{{ $customer->name }}</strong>
                            <br>
                            <small class="text-muted">{{ $customer->email }}</small>
                        </td>
                        <td>{{ $customer->phone_no }}</td>
                        <td>
                            @if($customer->license_no)
                                {{ $customer->license_no }}
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </td>
                        <td>
                            @if($customer->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $customer->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('staff.customers.show', $customer->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('staff.customers.edit', $customer->id) }}" 
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No customers found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection