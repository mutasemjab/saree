@extends('layouts.admin')

@section('title', __('messages.users'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ __('messages.users') }}</h3>
                        <div>
                            <a href="{{ route('users.export', request()->query()) }}" class="btn btn-success me-2">
                                <i class="fas fa-file-excel"></i> {{ __('messages.export_excel') }}
                            </a>
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('messages.add_user') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Search and Filter Form --}}
                        <form method="GET" action="{{ route('users.index') }}" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="{{ __('messages.search') }}..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-control">
                                        <option value="">{{ __('messages.all_status') }}</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                            {{ __('messages.active') }}
                                        </option>
                                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                                            {{ __('messages.inactive') }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="city_id" class="form-control">
                                        <option value="">{{ __('messages.all_cities') }}</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ request('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_from" class="form-control"
                                        placeholder="{{ __('messages.from_date') }}" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_to" class="form-control"
                                        placeholder="{{ __('messages.to_date') }}" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            @if (request()->hasAny(['search', 'status', 'city_id', 'date_from', 'date_to']))
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times"></i> {{ __('messages.clear_filters') }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </form>

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
                                                <img src="{{ asset('assets/admin/uploads') . '/' . $user->photo }}"
                                                    alt="{{ $user->name }}" class="rounded-circle" width="50"
                                                    height="50">
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>
                                                @if ($user->lat && $user->lng)
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
                                                    <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm">
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
                                                            <i
                                                                class="fas fa-{{ $user->activate == 1 ? 'ban' : 'check' }}"></i>
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
