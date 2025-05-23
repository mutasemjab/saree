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
                        <a href="{{ route('wallets.statistics') }}" class="btn btn-info me-2">
                            <i class="fas fa-chart-bar"></i> {{ __('messages.statistics') }}
                        </a>
                       <a href="{{ route('wallet-transactions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.add_transaction') }}
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                   

                    <!-- Filter Tabs -->
                    <ul class="nav nav-tabs mb-3" id="walletTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                                {{ __('messages.all_wallets') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="{{ route('wallets.by-owner-type', 'users') }}">
                                {{ __('messages.user_wallets') }}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="{{ route('wallets.by-owner-type', 'drivers') }}">
                                {{ __('messages.driver_wallets') }}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="{{ route('wallets.by-owner-type', 'admins') }}">
                                {{ __('messages.admin_wallets') }}
                            </a>
                        </li>
                    </ul>

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
                                                    <img src="{{ $wallet->user->photo_url }}" 
                                                         alt="{{ $wallet->user->name }}" 
                                                         class="rounded-circle me-2" 
                                                         width="30" height="30">
                                                    {{ $wallet->user->name }}
                                                </div>
                                            @elseif($wallet->driver)
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $wallet->driver->photo_url }}" 
                                                         alt="{{ $wallet->driver->name }}" 
                                                         class="rounded-circle me-2" 
                                                         width="30" height="30">
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
                                                <a href="{{ route('wallet-transactions.by-wallet', $wallet) }}" 
                                                   class="btn btn-secondary btn-sm">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                                <a href="{{ route('wallets.edit', $wallet) }}" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('wallets.destroy', $wallet) }}" 
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