@extends('layouts.admin')

@section('title', __('messages.order_details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.order_details') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Order Header -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <h4 class="mb-0 me-3">{{ $order->number }}</h4>
                                <span class="badge bg-{{ $order->status_color }} fs-6">
                                    {{ $order->status_text }}
                                </span>
                            </div>
                            <small class="text-muted">{{ __('messages.created_at') }}: {{ $order->created_at->format('Y-m-d H:i:s') }}</small>
                        </div>
                        <div class="col-md-6 text-end">
                            @if($order->final_price)
                                <h3 class="text-primary mb-0">{{ number_format($order->final_price, 2) }} {{ __('messages.currency') }}</h3>
                                <small class="text-muted">{{ __('messages.final_price') }}</small>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-user"></i> {{ __('messages.customer_information') }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($order->user)
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="{{ asset('assets/admin/uploads/' . $order->user->photo) }}" 
                                                 alt="{{ $order->user->name }}" 
                                                 class="rounded-circle me-3" 
                                                 width="60" height="60">
                                            <div>
                                                <h6 class="mb-0">{{ $order->user->name }}</h6>
                                                <p class="text-muted mb-0">{{ $order->user->phone }}</p>
                                                <span class="badge bg-{{ $order->user->activate == 1 ? 'success' : 'danger' }}">
                                                    {{ $order->user->activate == 1 ? __('messages.active') : __('messages.inactive') }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">{{ __('messages.no_customer_assigned') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Driver Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-car"></i> {{ __('messages.driver_information') }}
                                    </h5>
                                    @if(!$order->driver)
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignDriverModal">
                                            <i class="fas fa-plus"></i> {{ __('messages.assign_driver') }}
                                        </button>
                                    @endif
                                </div>
                                <div class="card-body">
                                    @if($order->driver)
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="{{ asset('assets/admin/uploads/' . $order->driver->photo) }}" 
                                                 alt="{{ $order->driver->name }}" 
                                                 class="rounded-circle me-3" 
                                                 width="60" height="60">
                                            <div>
                                                <h6 class="mb-0">{{ $order->driver->name }}</h6>
                                                <p class="text-muted mb-0">{{ $order->driver->phone }}</p>
                                                <span class="badge bg-{{ $order->driver->activate == 1 ? 'success' : 'danger' }}">
                                                    {{ $order->driver->activate == 1 ? __('messages.active') : __('messages.inactive') }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">{{ __('messages.no_driver_assigned') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Order Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-info-circle"></i> {{ __('messages.order_information') }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>{{ __('messages.price') }}:</strong><br>
                                            <span class="text-muted">{{ $order->price ? number_format($order->price, 2) . ' ' . __('messages.currency') : __('messages.not_set') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>{{ __('messages.discount') }}:</strong><br>
                                            <span class="text-muted">{{ $order->discount ? number_format($order->discount, 2) . ' ' . __('messages.currency') : __('messages.no_discount') }}</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>{{ __('messages.total_distance') }}:</strong><br>
                                            <span class="text-muted">{{ $order->total_distance ? number_format($order->total_distance, 2) . ' ' . __('messages.km') : __('messages.not_set') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>{{ __('messages.total_time') }}:</strong><br>
                                            <span class="text-muted">{{ $order->total_time ?? __('messages.not_set') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-credit-card"></i> {{ __('messages.payment_information') }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>{{ __('messages.payment_status') }}:</strong><br>
                                            <span class="badge bg-{{ $order->payment_type == 1 ? 'success' : 'warning' }}">
                                                {{ $order->payment_type == 1 ? __('messages.paid') : __('messages.unpaid') }}
                                            </span>
                                        </div>
                                        <div class="col-6">
                                            <strong>{{ __('messages.payment_method') }}:</strong><br>
                                            <span class="badge bg-{{ $order->payment_method == 1 ? 'info' : 'primary' }}">
                                                {{ $order->payment_method == 1 ? __('messages.cash') : __('messages.visa') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                        <i class="fas fa-sync"></i> {{ __('messages.update_status') }}
                                    </button>
                                </div>
                                
                                <div>
                                    <form action="{{ route('orders.destroy', $order) }}" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('{{ __('messages.confirm_delete_order') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> {{ __('messages.delete_order') }}
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

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('orders.update-status', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('messages.update_order_status') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal_order_status" class="form-label">{{ __('messages.order_status') }}</label>
                        <select class="form-select" id="modal_order_status" name="order_status" required>
                            <option value="1" {{ $order->order_status == 1 ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                            <option value="2" {{ $order->order_status == 2 ? 'selected' : '' }}>{{ __('messages.accepted') }}</option>
                            <option value="3" {{ $order->order_status == 3 ? 'selected' : '' }}>{{ __('messages.on_the_way') }}</option>
                            <option value="4" {{ $order->order_status == 4 ? 'selected' : '' }}>{{ __('messages.delivered') }}</option>
                            <option value="5" {{ $order->order_status == 5 ? 'selected' : '' }}>{{ __('messages.cancelled_by_user') }}</option>
                            <option value="6" {{ $order->order_status == 6 ? 'selected' : '' }}>{{ __('messages.cancelled_by_driver') }}</option>
                            <option value="7" {{ $order->order_status == 7 ? 'selected' : '' }}>{{ __('messages.no_drivers_availble') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Assign Driver Modal -->
<div class="modal fade" id="assignDriverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('orders.assign-driver', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('messages.assign_driver') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal_driver_id" class="form-label">{{ __('messages.driver') }}</label>
                        <select class="form-select" id="modal_driver_id" name="driver_id" required>
                            <option value="">{{ __('messages.select_driver') }}</option>
                            @foreach(\App\Models\Driver::active()->get() as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }} ({{ $driver->phone }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.assign') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection