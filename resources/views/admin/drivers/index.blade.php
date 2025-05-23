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
                
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.id') }}</th>
                                    <th>{{ __('messages.photo') }}</th>
                                    <th>{{ __('messages.name') }}</th>
                                    <th>{{ __('messages.phone') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($drivers as $driver)
                                    <tr>
                                        <td>{{ $driver->id }}</td>
                                        <td>
                                            <img src="{{ $driver->photo_url }}" 
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
                                                <form action="{{ route('drivers.destroy', $driver) }}" 
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
                                        <td colspan="7" class="text-center">
                                            {{ __('messages.no_drivers_found') }}
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