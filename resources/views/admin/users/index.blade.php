@extends('layouts.admin')

@section('title', __('messages.users'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.users') }}</h3>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('messages.add_user') }}
                    </a>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.id') }}</th>
                                    <th>{{ __('messages.photo') }}</th>
                                    <th>{{ __('messages.name') }}</th>
                                    <th>{{ __('messages.phone') }}</th>
                                    <th>{{ __('messages.location') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            <img src="{{ $user->photo_url }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="rounded-circle" 
                                                 width="50" height="50">
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>
                                            @if($user->lat && $user->lng)
                                                <small>
                                                    {{ __('messages.lat') }}: {{ number_format($user->lat, 6) }}<br>
                                                    {{ __('messages.lng') }}: {{ number_format($user->lng, 6) }}
                                                </small>
                                            @else
                                                <span class="text-muted">{{ __('messages.not_available') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->activate == 1 ? 'success' : 'danger' }}">
                                                {{ $user->activation_status }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('users.show', $user) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('users.edit', $user) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('users.toggle-activation', $user) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-{{ $user->activate == 1 ? 'secondary' : 'success' }} btn-sm"
                                                            title="{{ $user->activate == 1 ? __('messages.deactivate') : __('messages.activate') }}">
                                                        <i class="fas fa-{{ $user->activate == 1 ? 'ban' : 'check' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('users.destroy', $user) }}" 
                                                      method="POST" 
                                                      style="display: inline;"
                                                      onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            {{ __('messages.no_users_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection