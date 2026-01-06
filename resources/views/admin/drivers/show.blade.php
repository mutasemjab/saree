@extends('layouts.admin')

@section('title', __('messages.driver_details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.driver_details') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('drivers.edit', $driver) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                        </a>
                        <a href="{{ route('drivers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="driver-photo mb-3">
                                <img src="{{ asset('assets/admin/uploads') . '/' . $driver->photo }}" 
                                     alt="{{ $driver->name }}" 
                                     class="img-fluid rounded-circle border" 
                                     style="max-width: 200px; max-height: 200px;">
                            </div>
                            <h4>{{ $driver->name }}</h4>
                            <span class="badge bg-{{ $driver->activate == 1 ? 'success' : 'danger' }} fs-6">
                                {{ $driver->activation_status }}
                            </span>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted">{{ __('messages.id') }}</h6>
                                        <p class="h5">#{{ $driver->id }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted">{{ __('messages.phone') }}</h6>
                                        <p class="h5">
                                            <a href="tel:{{ $driver->phone }}" class="text-decoration-none">
                                                {{ $driver->phone }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted">{{ __('messages.created_at') }}</h6>
                                        <p class="h6">{{ $driver->created_at->format('Y-m-d H:i:s') }}</p>
                                        <small class="text-muted">{{ $driver->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted">{{ __('messages.updated_at') }}</h6>
                                        <p class="h6">{{ $driver->updated_at->format('Y-m-d H:i:s') }}</p>
                                        <small class="text-muted">{{ $driver->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <form action="{{ route('drivers.toggle-activation', $driver) }}" 
                                          method="POST" 
                                          style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-{{ $driver->activate == 1 ? 'warning' : 'success' }}">
                                            <i class="fas fa-{{ $driver->activate == 1 ? 'ban' : 'check' }}"></i>
                                            {{ $driver->activate == 1 ? __('messages.deactivate_driver') : __('messages.activate_driver') }}
                                        </button>
                                    </form>
                                </div>
                                
                                <div>
                                    <form action="{{ route('drivers.destroy', $driver) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('{{ __('messages.confirm_delete_driver') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> {{ __('messages.delete_driver') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('{{ __('messages.token_copied') }}');
    }, function(err) {
        console.error('{{ __('messages.copy_failed') }}', err);
    });
}
</script>
@endsection