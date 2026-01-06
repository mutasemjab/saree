@extends('layouts.admin')

@section('title', __('messages.drivers'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.drivers') }}</h3>
                    <a href="{{ route('drivers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('messages.add_driver') }}
                    </a>
                </div>
                
                <!-- Search and Filter Form -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('drivers.index') }}" class="row g-3">
                        <!-- Search Input -->
                        <div class="col-md-4">
                            <label for="search" class="form-label">{{ __('messages.search') }}</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="{{ __('messages.search_by_name_phone_id') }}">
                        </div>
                        
                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <label for="status" class="form-label">{{ __('messages.status') }}</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">{{ __('messages.all_status') }}</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                    {{ __('messages.active') }}
                                </option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                    {{ __('messages.inactive') }}
                                </option>
                            </select>
                        </div>
                        
                        <!-- Date From -->
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">{{ __('messages.from_date') }}</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_from" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                        
                        <!-- Date To -->
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">{{ __('messages.to_date') }}</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_to" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>
                        
                        <!-- Filter Buttons -->
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="btn-group w-100" role="group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> {{ __('messages.filter') }}
                                </button>
                                <a href="{{ route('drivers.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> {{ __('messages.clear') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <!-- Results Info -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="text-muted">
                                {{ __('messages.showing') }} {{ $drivers->firstItem() ?? 0 }} 
                                {{ __('messages.to') }} {{ $drivers->lastItem() ?? 0 }} 
                                {{ __('messages.of') }} {{ $drivers->total() }} 
                                {{ __('messages.results') }}
                            </span>
                        </div>
                        
                        <!-- Sort Options -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" 
                                    id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-sort"></i> {{ __('messages.sort_by') }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => 'asc']) }}">
                                        {{ __('messages.name') }} (A-Z)
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => 'desc']) }}">
                                        {{ __('messages.name') }} (Z-A)
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => 'desc']) }}">
                                        {{ __('messages.newest_first') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => 'asc']) }}">
                                        {{ __('messages.oldest_first') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ __('messages.id') }}
                                            @if(request('sort_by') === 'id')
                                                <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>{{ __('messages.photo') }}</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ __('messages.name') }}
                                            @if(request('sort_by') === 'name')
                                                <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>{{ __('messages.phone') }}</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'activate', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ __('messages.status') }}
                                            @if(request('sort_by') === 'activate')
                                                <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none text-dark">
                                            {{ __('messages.created_at') }}
                                            @if(request('sort_by') === 'created_at' || !request('sort_by'))
                                                <i class="fas fa-sort-{{ (request('sort_order') === 'asc') ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($drivers as $driver)
                                    <tr>
                                        <td>{{ $driver->id }}</td>
                                        <td>
                                            <img src="{{ asset('assets/admin/uploads') . '/' . $driver->photo }}" 
                                                 alt="{{ $driver->name }}" 
                                                 class="rounded-circle" 
                                                 width="50" height="50">
                                        </td>
                                        <td>{{ $driver->name }}</td>
                                        <td>{{ $driver->phone }}</td>
                                        <td>
                                            <span class="badge bg-{{ $driver->activate == 1 ? 'success' : 'danger' }}">
                                                {{ $driver->activation_status }}
                                            </span>
                                        </td>
                                        <td>{{ $driver->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('drivers.show', $driver) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('drivers.edit', $driver) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('drivers.toggle-activation', $driver) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-{{ $driver->activate == 1 ? 'secondary' : 'success' }} btn-sm"
                                                            title="{{ $driver->activate == 1 ? __('messages.deactivate') : __('messages.activate') }}">
                                                        <i class="fas fa-{{ $driver->activate == 1 ? 'ban' : 'check' }}"></i>
                                                    </button>
                                                </form>
                                              
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            @if(request()->anyFilled(['search', 'status', 'date_from', 'date_to']))
                                                {{ __('messages.no_drivers_found_with_filters') }}
                                                <br>
                                                <a href="{{ route('drivers.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                                    {{ __('messages.clear_filters') }}
                                                </a>
                                            @else
                                                {{ __('messages.no_drivers_found') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $drivers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    // Auto-submit form on status change (optional)
    document.getElementById('status').addEventListener('change', function() {
        // Uncomment the line below if you want auto-submit on status change
        // this.form.submit();
    });
    
    // Clear form function
    function clearFilters() {
        window.location.href = "{{ route('drivers.index') }}";
    }
    
    // Enter key search
    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            this.form.submit();
        }
    });
</script>
@endsection
