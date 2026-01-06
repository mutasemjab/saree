@extends('layouts.admin')

@section('title', __('messages.wallets'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.wallets') }}</h3>
                    <div>
                       <a href="{{ route('wallets.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.add_transaction') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                   
            <form method="GET" action="{{ route('wallets.index') }}">
                        <select name="type" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">{{ __('messages.all_types') }}</option>
                            <option value="user" {{ request('type') == 'user' ? 'selected' : '' }}>{{ __('messages.user') }}</option>
                            <option value="driver" {{ request('type') == 'driver' ? 'selected' : '' }}>{{ __('messages.driver') }}</option>
                            <option value="admin" {{ request('type') == 'admin' ? 'selected' : '' }}>{{ __('messages.admin') }}</option>
                        </select>
                    </form>
                    <br>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.id') }}</th>
                                    <th>{{ __('messages.owner') }}</th>
                                    <th>{{ __('messages.owner_type') }}</th>
                                    <th>{{ __('messages.total_balance') }}</th>
                                    <th>{{ __('messages.transactions_count') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wallets as $wallet)
                                    <tr>
                                        <td>{{ $wallet->id }}</td>
                                        <td>
                                            @if($wallet->user)
                                                <div class="d-flex align-items-center">
                                               
                                                    {{ $wallet->user->name }}
                                                </div>
                                            @elseif($wallet->driver)
                                                <div class="d-flex align-items-center">
                                              
                                                    {{ $wallet->driver->name }}
                                                </div>
                                            @elseif($wallet->admin)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-shield me-2"></i>
                                                    {{ $wallet->admin->name ?? __('messages.admin') }}
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('messages.no_owner') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($wallet->user)
                                                <span class="badge bg-primary">{{ __('messages.user') }}</span>
                                            @elseif($wallet->driver)
                                                <span class="badge bg-success">{{ __('messages.driver') }}</span>
                                            @elseif($wallet->admin)
                                                <span class="badge bg-warning">{{ __('messages.admin') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('messages.unknown') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-{{ $wallet->total >= 0 ? 'success' : 'danger' }}">
                                                {{ number_format($wallet->total, 2) }} {{ __('messages.currency') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $wallet->transactions_count ?? 0 }}</span>
                                        </td>
                                        <td>{{ $wallet->created_at ? $wallet->created_at->format('Y-m-d H:i') : '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('wallets.show', $wallet) }}" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            {{ __('messages.no_wallets_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $wallets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection