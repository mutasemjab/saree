@extends('layouts.admin')

@section('title', __('messages.user_details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.user_details') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="user-photo mb-3">
                                <img src="{{ $user->photo_url }}" 
                                     alt="{{ $user->name }}" 
                                     class="img-fluid rounded-circle border" 
                                     style="max-width: 200px; max-height: 200px;">
                            </div>
                            <h4>{{ $user->name }}</h4>
                            <span class="badge bg-{{ $user->activate == 1 ? 'success' : 'danger' }} fs-6">
                                {{ $user->activation_status }}
                            </span>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted">{{ __('messages.id') }}</h6>
                                        <p class="h5">#{{ $user->id }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted">{{ __('messages.phone') }}</h6>
                                        <p class="h5">
                                            <a href="tel:{{ $user->phone }}" class="text-decoration-none">
                                                {{ $user->phone }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted">{{ __('messages.created_at') }}</h6>
                                        <p class="h6">{{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted">{{ __('messages.updated_at') }}</h6>
                                        <p class="h6">{{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
                                        <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-map-marker-alt"></i> {{ __('messages.location_info') }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($user->lat && $user->lng)
                                        <div class="row">
                                            <div class="col-6">
                                                <strong>{{ __('messages.latitude') }}:</strong><br>
                                                <span class="text-muted">{{ number_format($user->lat, 6) }}</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>{{ __('messages.longitude') }}:</strong><br>
                                                <span class="text-muted">{{ number_format($user->lng, 6) }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <a href="https://www.google.com/maps?q={{ $user->lat }},{{ $user->lng }}" 
                                               target="_blank" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-external-link-alt"></i> {{ __('messages.view_on_map') }}
                                            </a>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">{{ __('messages.location_not_available') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-mobile-alt"></i> {{ __('messages.device_info') }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($user->fcm_token)
                                        <div class="mb-2">
                                            <strong>{{ __('messages.fcm_token') }}:</strong>
                                        </div>
                                        <div class="bg-light p-2 rounded" style="font-family: monospace; font-size: 0.85em; word-break: break-all;">
                                            {{ $user->fcm_token }}
                                        </div>
                                        <div class="mt-2">
                                            <button class="btn btn-outline-secondary btn-sm" 
                                                    onclick="copyToClipboard('{{ $user->fcm_token }}')">
                                                <i class="fas fa-copy"></i> {{ __('messages.copy_token') }}
                                            </button>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">{{ __('messages.fcm_token_not_available') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <form action="{{ route('users.toggle-activation', $user) }}" 
                                          method="POST" 
                                          style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-{{ $user->activate == 1 ? 'warning' : 'success' }}">
                                            <i class="fas fa-{{ $user->activate == 1 ? 'ban' : 'check' }}"></i>
                                            {{ $user->activate == 1 ? __('messages.deactivate_user') : __('messages.activate_user') }}
                                        </button>
                                    </form>
                                </div>
                                
                                <div>
                                    <form action="{{ route('users.destroy', $user) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('{{ __('messages.confirm_delete_user') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> {{ __('messages.delete_user') }}
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