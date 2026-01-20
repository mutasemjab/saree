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
                    <!-- Driver Profile Section -->
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="driver-photo mb-3">
                                <img src="{{ asset('assets/admin/uploads') . '/' . $driver->photo }}"
                                     alt="{{ $driver->name }}"
                                     class="img-fluid rounded-circle border"
                                     style="max-width: 200px; max-height: 200px;">
                            </div>
                            <h4>{{ $driver->name }}</h4>
                            <span class="badge bg-{{ $driver->activate == 1 ? 'success' : 'danger' }} fs-6 mb-2">
                                {{ $driver->activation_status }}
                            </span>
                            <br>
                            <span class="badge bg-{{ $driver->status == 1 ? 'success' : 'secondary' }} fs-6">
                                {{ $driver->status == 1 ? __('messages.online') : __('messages.offline') }}
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
                                        <h6 class="text-muted">{{ __('messages.identity_number') }}</h6>
                                        <p class="h5">{{ $driver->identity_number }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted">{{ __('messages.plate_number') }}</h6>
                                        <p class="h5">{{ $driver->plate_number ?? __('messages.not_available') }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted">{{ __('messages.Car type') }}</h6>
                                        <p class="h5">
                                            <span class="badge bg-primary">
                                                {{ $driver->car_type == 1 ? __('messages.car') : __('messages.motosycle') }}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted">{{ __('messages.City') }}</h6>
                                        <p class="h5">{{ $driver->city->name ?? __('messages.not_available') }}</p>
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

                    <!-- Statistics Section -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">{{ __('messages.statistics') }}</h5>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $statistics['total_orders'] }}</h3>
                                    <p class="mb-0">{{ __('messages.total_orders') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $statistics['pending_orders'] }}</h3>
                                    <p class="mb-0">{{ __('messages.pending') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $statistics['completed_orders'] }}</h3>
                                    <p class="mb-0">{{ __('messages.completed') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $statistics['cancelled_orders'] }}</h3>
                                    <p class="mb-0">{{ __('messages.cancelled') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3>{{ number_format($statistics['total_earnings'], 2) }}</h3>
                                    <p class="mb-0">{{ __('messages.total_earnings') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ number_format($statistics['total_distance'], 2) }}</h3>
                                    <p class="mb-0">{{ __('messages.total_km') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Wallet Section -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">{{ __('messages.wallet_information') }}</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="text-muted">{{ __('messages.wallet_id') }}</h6>
                                    <p class="h4">#{{ $driver->wallet->id ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="text-muted">{{ __('messages.current_balance') }}</h6>
                                    <p class="h4 text-success">{{ number_format($driver->wallet->total ?? 0, 2) }} {{ __('messages.currency') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <a href="{{ route('wallets.show', $driver->wallet->id ?? 0) }}" class="btn btn-primary">
                                        <i class="fas fa-wallet"></i> {{ __('messages.view_wallet') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Recent Transactions -->
                    @if($driver->wallet && $driver->wallet->transactions->count() > 0)
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">{{ __('messages.recent_transactions') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.id') }}</th>
                                            <th>{{ __('messages.type') }}</th>
                                            <th>{{ __('messages.amount') }}</th>
                                            <th>{{ __('messages.note') }}</th>
                                            <th>{{ __('messages.date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($driver->wallet->transactions as $transaction)
                                        <tr>
                                            <td>#{{ $transaction->id }}</td>
                                            <td>
                                                @if($transaction->deposit > 0)
                                                    <span class="badge bg-success">{{ __('messages.deposit') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('messages.withdrawal') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($transaction->deposit > 0)
                                                    <span class="text-success">+{{ number_format($transaction->deposit, 2) }}</span>
                                                @else
                                                    <span class="text-danger">-{{ number_format($transaction->withdrawal, 2) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $transaction->note ?? '-' }}</td>
                                            <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @endif

                    <!-- Recent Orders -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">{{ __('messages.recent_orders') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.order_number') }}</th>
                                            <th>{{ __('messages.customer') }}</th>
                                            <th>{{ __('messages.status') }}</th>
                                            <th>{{ __('messages.price') }}</th>
                                            <th>{{ __('messages.driver_earnings') }}</th>
                                            <th>{{ __('messages.payment_method') }}</th>
                                            <th>{{ __('messages.date') }}</th>
                                            <th>{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                        <tr>
                                            <td>#{{ $order->number }}</td>
                                            <td>{{ $order->user->name ?? __('messages.not_available') }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        1 => 'warning',
                                                        2 => 'info',
                                                        3 => 'primary',
                                                        4 => 'success',
                                                        5 => 'danger',
                                                        6 => 'danger'
                                                    ];
                                                    $statusLabels = [
                                                        1 => __('messages.pending'),
                                                        2 => __('messages.accepted'),
                                                        3 => __('messages.on_the_way'),
                                                        4 => __('messages.delivered'),
                                                        5 => __('messages.cancelled_by_user'),
                                                        6 => __('messages.cancelled_by_driver')
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$order->order_status] ?? 'secondary' }}">
                                                    {{ $statusLabels[$order->order_status] ?? __('messages.unknown') }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($order->final_price, 2) }}</td>
                                            <td>{{ number_format($order->driver_earnings, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->payment_method == 1 ? 'success' : 'primary' }}">
                                                    {{ $order->payment_method == 1 ? __('messages.cash') : __('messages.visa') }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <a href="{{ route('orders.show', $order) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">{{ __('messages.no_orders_found') }}</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-3">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Action Buttons -->
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
@endsection
