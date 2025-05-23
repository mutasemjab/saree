@extends('layouts.admin')

@section('title', __('messages.edit_transaction'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_transaction') }} #{{ $transaction->id }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('wallets.show', $transaction->wallet) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                
                <!-- Current Transaction Info -->
                <div class="card-body bg-light border-bottom">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mb-2">{{ __('messages.current_transaction_info') }}</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>{{ __('messages.type') }}:</strong>
                                    @if($transaction->deposit > 0)
                                        <span class="badge badge-success">
                                            <i class="fas fa-arrow-up"></i> {{ __('messages.deposit') }}
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-arrow-down"></i> {{ __('messages.withdrawal') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <strong>{{ __('messages.amount') }}:</strong>
                                    @if($transaction->deposit > 0)
                                        <span class="text-success">{{ number_format($transaction->deposit, 2) }} {{ __('messages.currency') }}</span>
                                    @else
                                        <span class="text-danger">{{ number_format($transaction->withdrawal, 2) }} {{ __('messages.currency') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <strong>{{ __('messages.wallet') }}:</strong>
                                    #{{ $transaction->wallet->id }}
                                </div>
                                <div class="col-md-3">
                                    <strong>{{ __('messages.created_at') }}:</strong>
                                    {{ $transaction->created_at->format('Y-m-d H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('wallets.update', $transaction) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="wallet_id" class="form-label">{{ __('messages.wallet') }} <span class="text-danger">*</span></label>
                                    <select class="form-control @error('wallet_id') is-invalid @enderror" 
                                            id="wallet_id" 
                                            name="wallet_id" 
                                            required>
                                        <option value="">{{ __('messages.select_wallet') }}</option>
                                        @foreach($wallets as $wallet)
                                            <option value="{{ $wallet->id }}" 
                                                {{ (old('wallet_id', $transaction->wallet_id) == $wallet->id) ? 'selected' : '' }}>
                                                #{{ $wallet->id }} - 
                                                @if($wallet->user)
                                                    {{ $wallet->user->name }} ({{ __('messages.user') }})
                                                @elseif($wallet->driver)
                                                    {{ $wallet->driver->name }} ({{ __('messages.driver') }})
                                                @elseif($wallet->admin)
                                                    {{ $wallet->admin->name ?? __('messages.admin') }} ({{ __('messages.admin') }})
                                                @endif
                                                - {{ number_format($wallet->total, 2) }} {{ __('messages.currency') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('wallet_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">{{ __('messages.transaction_type') }}</h5>
                                <p class="text-muted">{{ __('messages.select_transaction_type_help') }}</p>
                                
                                @error('transaction')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="deposit" class="form-label">{{ __('messages.deposit') }}</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('deposit') is-invalid @enderror" 
                                               id="deposit" 
                                               name="deposit" 
                                               value="{{ old('deposit', $transaction->deposit > 0 ? $transaction->deposit : 0) }}" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text">{{ __('messages.currency') }}</span>
                                    </div>
                                    @error('deposit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-success">{{ __('messages.deposit_help') }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="withdrawal" class="form-label">{{ __('messages.withdrawal') }}</label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('withdrawal') is-invalid @enderror" 
                                               id="withdrawal" 
                                               name="withdrawal" 
                                               value="{{ old('withdrawal', $transaction->withdrawal > 0 ? $transaction->withdrawal : 0) }}" 
                                               step="0.01"
                                               min="0">
                                        <span class="input-group-text">{{ __('messages.currency') }}</span>
                                    </div>
                                    @error('withdrawal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-danger">{{ __('messages.withdrawal_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="note" class="form-label">{{ __('messages.note') }}</label>
                                    <textarea class="form-control @error('note') is-invalid @enderror" 
                                              id="note" 
                                              name="note" 
                                              rows="3"
                                              placeholder="{{ __('messages.transaction_note_placeholder') }}">{{ old('note', $transaction->note) }}</textarea>
                                    @error('note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields for user/driver/admin relationships -->
                        <input type="hidden" name="admin_id" value="{{ old('admin_id', $transaction->admin_id) }}">
                        <input type="hidden" name="user_id" value="{{ old('user_id', $transaction->user_id) }}">
                        <input type="hidden" name="driver_id" value="{{ old('driver_id', $transaction->driver_id) }}">
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.update') }}
                        </button>
                        <a href="{{ route('wallets.show', $transaction->wallet) }}" class="btn btn-secondary">
                            {{ __('messages.cancel') }}
                        </a>
                        
                        <!-- Delete Button -->
                        <button type="button" class="btn btn-danger float-right" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('messages.confirm_delete') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('messages.delete_transaction_confirmation') }}</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ __('messages.delete_transaction_warning') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.cancel') }}</button>
                <form action="{{ route('wallets.destroy', $transaction) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('messages.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Ensure only deposit OR withdrawal is filled
document.addEventListener('DOMContentLoaded', function() {
    const depositInput = document.getElementById('deposit');
    const withdrawalInput = document.getElementById('withdrawal');
    
    depositInput.addEventListener('input', function() {
        if (this.value > 0) {
            withdrawalInput.value = 0;
        }
    });
    
    withdrawalInput.addEventListener('input', function() {
        if (this.value > 0) {
            depositInput.value = 0;
        }
    });
});
</script>
@endsection