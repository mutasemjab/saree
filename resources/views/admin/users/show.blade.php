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
                    <!-- User Profile Section -->
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="user-photo mb-3">
                                <img src="{{ asset('assets/admin/uploads') . '/' . $user->photo }}"
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
                                        <h6 class="text-muted">{{ __('messages.City') }}</h6>
                                        <p class="h5">{{ $user->city->name ?? __('messages.not_available') }}</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <h6 class="text-muted">{{ __('messages.total_addresses') }}</h6>
                                        <p class="h5">{{ $statistics['total_addresses'] }}</p>
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

                    <!-- Statistics Section -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">{{ __('messages.statistics') }}</h5>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $statistics['total_orders'] }}</h3>
                                    <p class="mb-0">{{ __('messages.total_orders') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $statistics['pending_orders'] }}</h3>
                                    <p class="mb-0">{{ __('messages.pending') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $statistics['completed_orders'] }}</h3>
                                    <p class="mb-0">{{ __('messages.completed') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $statistics['cancelled_orders'] }}</h3>
                                    <p class="mb-0">{{ __('messages.cancelled') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Location and Wallet Section -->
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
                                        <i class="fas fa-wallet"></i> {{ __('messages.wallet_information') }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($user->wallet)
                                        <div class="row">
                                            <div class="col-6">
                                                <strong>{{ __('messages.wallet_id') }}:</strong><br>
                                                <span class="text-muted">#{{ $user->wallet->id }}</span>
                                            </div>
                                            <div class="col-6">
                                                <strong>{{ __('messages.current_balance') }}:</strong><br>
                                                <span class="text-success h5">{{ number_format($user->wallet->total, 2) }} {{ __('messages.currency') }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('wallets.show', $user->wallet->id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> {{ __('messages.view_wallet') }}
                                            </a>
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">{{ __('messages.no_wallet') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- User Addresses -->
                    @if($user->addresses && $user->addresses->count() > 0)
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">{{ __('messages.saved_addresses') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.id') }}</th>
                                            <th>{{ __('messages.title') }}</th>
                                            <th>{{ __('messages.address') }}</th>
                                            <th>{{ __('messages.location') }}</th>
                                            <th>{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->addresses as $address)
                                        <tr>
                                            <td>#{{ $address->id }}</td>
                                            <td>{{ $address->title ?? '-' }}</td>
                                            <td>{{ $address->address ?? '-' }}</td>
                                            <td>
                                                @if($address->lat && $address->lng)
                                                    <a href="https://www.google.com/maps?q={{ $address->lat }},{{ $address->lng }}"
                                                       target="_blank"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-map-marker-alt"></i> {{ __('messages.view_on_map') }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $address->created_at->format('Y-m-d') }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @endif

                    <!-- Recent Transactions -->
                    @if($user->wallet && $user->wallet->transactions->count() > 0)
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
                                        @foreach($user->wallet->transactions as $transaction)
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
                                            <th>{{ __('messages.driver') }}</th>
                                            <th>{{ __('messages.status') }}</th>
                                            <th>{{ __('messages.price') }}</th>
                                            <th>{{ __('messages.payment_method') }}</th>
                                            <th>{{ __('messages.date') }}</th>
                                            <th>{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                        <tr>
                                            <td>#{{ $order->number }}</td>
                                            <td>{{ $order->driver->name ?? __('messages.not_assigned') }}</td>
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
                                            <td colspan="7" class="text-center">{{ __('messages.no_orders_found') }}</td>
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
@endsection
