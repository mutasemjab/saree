@extends('layouts.admin')

@section('title', __('messages.edit_order'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_order') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="number" class="form-label">{{ __('messages.order_number') }}</label>
                                    <input type="text" 
                                           class="form-control @error('number') is-invalid @enderror" 
                                           id="number" 
                                           name="number" 
                                           value="{{ old('number', $order->number) }}">
                                    @error('number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="order_status" class="form-label">{{ __('messages.order_status') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('order_status') is-invalid @enderror" 
                                            id="order_status" 
                                            name="order_status" 
                                            required>
                                        <option value="">{{ __('messages.select_status') }}</option>
                                        <option value="1" {{ old('order_status', $order->order_status) == '1' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                                        <option value="2" {{ old('order_status', $order->order_status) == '2' ? 'selected' : '' }}>{{ __('messages.accepted') }}</option>
                                        <option value="3" {{ old('order_status', $order->order_status) == '3' ? 'selected' : '' }}>{{ __('messages.on_the_way') }}</option>
                                        <option value="4" {{ old('order_status', $order->order_status) == '4' ? 'selected' : '' }}>{{ __('messages.delivered') }}</option>
                                        <option value="5" {{ old('order_status', $order->order_status) == '5' ? 'selected' : '' }}>{{ __('messages.cancelled_by_user') }}</option>
                                        <option value="6" {{ old('order_status', $order->order_status) == '6' ? 'selected' : '' }}>{{ __('messages.cancelled_by_driver') }}</option>
                                    </select>
                                    @error('order_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="user_id" class="form-label">{{ __('messages.customer') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" 
                                            id="user_id" 
                                            name="user_id" 
                                            required>
                                        <option value="">{{ __('messages.select_customer') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="driver_id" class="form-label">{{ __('messages.driver') }}</label>
                                    <select class="form-select @error('driver_id') is-invalid @enderror" 
                                            id="driver_id" 
                                            name="driver_id">
                                        <option value="">{{ __('messages.select_driver') }}</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ old('driver_id', $order->driver_id) == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->name }} ({{ $driver->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('driver_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="price" class="form-label">{{ __('messages.price') }}</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               id="price" 
                                               name="price" 
                                               value="{{ old('price', $order->price) }}" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text">{{ __('messages.currency') }}</span>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="discount" class="form-label">{{ __('messages.discount') }}</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('discount') is-invalid @enderror" 
                                               id="discount" 
                                               name="discount" 
                                               value="{{ old('discount', $order->discount) }}" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text">{{ __('messages.currency') }}</span>
                                    </div>
                                    @error('discount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="final_price" class="form-label">{{ __('messages.final_price') }}</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('final_price') is-invalid @enderror" 
                                               id="final_price" 
                                               name="final_price" 
                                               value="{{ old('final_price', $order->final_price) }}" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text">{{ __('messages.currency') }}</span>
                                    </div>
                                    @error('final_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.auto_calculated_if_empty') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="total_distance" class="form-label">{{ __('messages.total_distance') }}</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('total_distance') is-invalid @enderror" 
                                               id="total_distance" 
                                               name="total_distance" 
                                               value="{{ old('total_distance', $order->total_distance) }}" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text">{{ __('messages.km') }}</span>
                                    </div>
                                    @error('total_distance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="total_time" class="form-label">{{ __('messages.total_time') }}</label>
                                    <input type="text" 
                                           class="form-control @error('total_time') is-invalid @enderror" 
                                           id="total_time" 
                                           name="total_time" 
                                           value="{{ old('total_time', $order->total_time) }}" 
                                           placeholder="{{ __('messages.time_format_example') }}">
                                    @error('total_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="payment_type" class="form-label">{{ __('messages.payment_status') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('payment_type') is-invalid @enderror" 
                                            id="payment_type" 
                                            name="payment_type" 
                                            required>
                                        <option value="">{{ __('messages.select_payment_status') }}</option>
                                        <option value="1" {{ old('payment_type', $order->payment_type) == '1' ? 'selected' : '' }}>{{ __('messages.paid') }}</option>
                                        <option value="2" {{ old('payment_type', $order->payment_type) == '2' ? 'selected' : '' }}>{{ __('messages.unpaid') }}</option>
                                    </select>
                                    @error('payment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="payment_method" class="form-label">{{ __('messages.payment_method') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" 
                                            name="payment_method" 
                                            required>
                                        <option value="">{{ __('messages.select_payment_method') }}</option>
                                        <option value="1" {{ old('payment_method', $order->payment_method) == '1' ? 'selected' : '' }}>{{ __('messages.cash') }}</option>
                                        <option value="2" {{ old('payment_method', $order->payment_method) == '2' ? 'selected' : '' }}>{{ __('messages.visa') }}</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Order History -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-history"></i> {{ __('messages.order_history') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>{{ __('messages.created_at') }}:</strong><br>
                                                <span class="text-muted">{{ $order->created_at->format('Y-m-d H:i:s') }}</span><br>
                                                <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>{{ __('messages.updated_at') }}:</strong><br>
                                                <span class="text-muted">{{ $order->updated_at->format('Y-m-d H:i:s') }}</span><br>
                                                <small class="text-muted">{{ $order->updated_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>{{ __('messages.current_status') }}:</strong><br>
                                                <span class="badge bg-{{ $order->status_color }}">{{ $order->status_text }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>{{ __('messages.current_final_price') }}:</strong><br>
                                                <span class="text-success fw-bold">{{ $order->formatted_final_price }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.update') }}
                        </button>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> {{ __('messages.view_details') }}
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            {{ __('messages.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto calculate final price
document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('price');
    const discountInput = document.getElementById('discount');
    const finalPriceInput = document.getElementById('final_price');
    
    function calculateFinalPrice() {
        const price = parseFloat(priceInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const finalPrice = price - discount;
        
        if (price > 0) {
            // Only auto-calculate if final price is empty or user wants to recalculate
            if (!finalPriceInput.value || confirm('{{ __('messages.recalculate_final_price') }}')) {
                finalPriceInput.value = finalPrice.toFixed(2);
            }
        }
    }
    
    priceInput.addEventListener('input', calculateFinalPrice);
    discountInput.addEventListener('input', calculateFinalPrice);
});
</script>
@endsection