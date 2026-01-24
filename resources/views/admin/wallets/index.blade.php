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

                <!-- Search and Filter Form -->
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('wallets.index') }}" class="row g-3">
                        <!-- Search Input -->
                        <div class="col-md-3">
                            <label for="search" class="form-label">{{ __('messages.search') }}</label>
                            <input type="text"
                                   class="form-control"
                                   id="search"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="{{ __('messages.search_wallet') }}">
                        </div>

                        <!-- Owner Type Filter -->
                        <div class="col-md-2">
                            <label for="type" class="form-label">{{ __('messages.owner_type') }}</label>
                            <select name="type" id="type" class="form-control">
                                <option value="">{{ __('messages.all_types') }}</option>
                                <option value="user" {{ request('type') == 'user' ? 'selected' : '' }}>
                                    {{ __('messages.user') }}
                                </option>
                                <option value="driver" {{ request('type') == 'driver' ? 'selected' : '' }}>
                                    {{ __('messages.driver') }}
                                </option>
                           
                            </select>
                        </div>

                        <!-- Balance Status Filter -->
                        <div class="col-md-2">
                            <label for="balance_status" class="form-label">{{ __('messages.balance_status') }}</label>
                            <select name="balance_status" id="balance_status" class="form-control">
                                <option value="">{{ __('messages.all_balances') }}</option>
                                <option value="positive" {{ request('balance_status') == 'positive' ? 'selected' : '' }}>
                                    {{ __('messages.positive') }}
                                </option>
                                <option value="negative" {{ request('balance_status') == 'negative' ? 'selected' : '' }}>
                                    {{ __('messages.negative') }}
                                </option>
                                <option value="zero" {{ request('balance_status') == 'zero' ? 'selected' : '' }}>
                                    {{ __('messages.zero') }}
                                </option>
                            </select>
                        </div>

                        <!-- Balance Min -->
                        <div class="col-md-2">
                            <label for="balance_min" class="form-label">{{ __('messages.balance_min') }}</label>
                            <input type="number"
                                   class="form-control"
                                   id="balance_min"
                                   name="balance_min"
                                   step="0.01"
                                   value="{{ request('balance_min') }}"
                                   placeholder="0.00">
                        </div>

                        <!-- Balance Max -->
                        <div class="col-md-2">
                            <label for="balance_max" class="form-label">{{ __('messages.balance_max') }}</label>
                            <input type="number"
                                   class="form-control"
                                   id="balance_max"
                                   name="balance_max"
                                   step="0.01"
                                   value="{{ request('balance_max') }}"
                                   placeholder="1000.00">
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

                        <!-- Sort By -->
                        <div class="col-md-2">
                            <label for="sort_by" class="form-label">{{ __('messages.sort_by') }}</label>
                            <select name="sort_by" id="sort_by" class="form-control">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>
                                    {{ __('messages.date') }}
                                </option>
                                <option value="total" {{ request('sort_by') == 'total' ? 'selected' : '' }}>
                                    {{ __('messages.balance') }}
                                </option>
                                <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>
                                    {{ __('messages.id') }}
                                </option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-2">
                            <label for="sort_order" class="form-label">{{ __('messages.order') }}</label>
                            <select name="sort_order" id="sort_order" class="form-control">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>
                                    {{ __('messages.descending') }}
                                </option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>
                                    {{ __('messages.ascending') }}
                                </option>
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="btn-group w-100" role="group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> {{ __('messages.filter') }}
                                </button>
                                <a href="{{ route('wallets.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
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
                                {{ __('messages.showing') }} {{ $wallets->firstItem() ?? 0 }}
                                {{ __('messages.to') }} {{ $wallets->lastItem() ?? 0 }}
                                {{ __('messages.of') }} {{ $wallets->total() }}
                                {{ __('messages.results') }}
                            </span>
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
                                    <th>{{ __('messages.owner') }}</th>
                                    <th>{{ __('messages.owner_type') }}</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'total', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}"
                                           class="text-decoration-none text-dark">
                                            {{ __('messages.total_balance') }}
                                            @if(request('sort_by') === 'total')
                                                <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>{{ __('messages.transactions_count') }}</th>
                                    <th>
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_order') === 'asc' ? 'desc' : 'asc']) }}"
                                           class="text-decoration-none text-dark">
                                            {{ __('messages.created_at') }}
                                            @if(request('sort_by') === 'created_at' || !request('sort_by'))
                                                <i class="fas fa-sort-{{ request('sort_order') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
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
                                                    <i class="fas fa-user me-2 text-primary"></i>
                                                    {{ $wallet->user->name }}
                                                </div>
                                            @elseif($wallet->driver)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-car me-2 text-success"></i>
                                                    {{ $wallet->driver->name }}
                                                </div>
                                            @elseif($wallet->admin)
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-shield me-2 text-warning"></i>
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
                                            <span class="fw-bold text-{{ $wallet->total > 0 ? 'success' : ($wallet->total < 0 ? 'danger' : 'secondary') }}">
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
                                            @if(request()->anyFilled(['search', 'type', 'balance_status', 'balance_min', 'balance_max', 'date_from', 'date_to']))
                                                {{ __('messages.no_wallets_found_with_filters') }}
                                                <br>
                                                <a href="{{ route('wallets.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                                    {{ __('messages.clear_filters') }}
                                                </a>
                                            @else
                                                {{ __('messages.no_wallets_found') }}
                                            @endif
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
