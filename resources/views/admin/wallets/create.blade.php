@extends('layouts.admin')

@section('title', __('messages.add_transaction'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.add_transaction') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('wallets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('wallets.store') }}" method="POST" id="transactionForm">
                    @csrf
                    <div class="card-body">
                        <!-- Step 1: Select Owner Type -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3">{{ __('messages.step') }} 1: {{ __('messages.select_owner_type') }}</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="owner_type" class="form-label">{{ __('messages.owner_type') }} <span class="text-danger">*</span></label>
                                    <select class="form-control @error('owner_type') is-invalid @enderror"
                                            id="owner_type"
                                            name="owner_type"
                                            required>
                                        <option value="">{{ __('messages.select_owner_type') }}</option>
                                        <option value="user" {{ old('owner_type') == 'user' ? 'selected' : '' }}>
                                            {{ __('messages.user') }}
                                        </option>
                                        <option value="driver" {{ old('owner_type') == 'driver' ? 'selected' : '' }}>
                                            {{ __('messages.driver') }}
                                        </option>
                                    </select>
                                    @error('owner_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Step 2: Select User/Driver -->
                        <div id="ownerSelectionSection" style="display: none;">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">{{ __('messages.step') }} 2: <span id="ownerTypeLabel"></span></h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="owner_id" class="form-label">
                                            <span id="ownerLabel"></span> <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control select2 @error('user_id') is-invalid @enderror @error('driver_id') is-invalid @enderror"
                                                id="owner_id"
                                                name="owner_id"
                                                data-placeholder="{{ __('messages.search_select_owner') }}"
                                                required>
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @error('driver_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ __('messages.current_balance') }}</label>
                                        <div class="alert alert-info" id="currentBalance">
                                            <i class="fas fa-wallet"></i>
                                            <span id="balanceAmount">0.00</span> {{ __('messages.currency') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden wallet_id field -->
                            <input type="hidden" name="wallet_id" id="wallet_id">
                            <input type="hidden" name="user_id" id="user_id">
                            <input type="hidden" name="driver_id" id="driver_id">

                            <hr>

                            <!-- Step 3: Transaction Details -->
                            <div id="transactionSection" style="display: none;">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="mb-3">{{ __('messages.step') }} 3: {{ __('messages.transaction_details') }}</h5>
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
                                                       value="{{ old('deposit', 0) }}"
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
                                                       value="{{ old('withdrawal', 0) }}"
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
                                                      placeholder="{{ __('messages.transaction_note_placeholder') }}">{{ old('note') }}</textarea>
                                            @error('note')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;">
                            <i class="fas fa-save"></i> {{ __('messages.save') }}
                        </button>
                        <a href="{{ route('wallets.index') }}" class="btn btn-secondary">
                            {{ __('messages.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    let ownerType = '';

    // When owner type is selected
    $('#owner_type').on('change', function() {
        ownerType = $(this).val();

        if (ownerType) {
            // Show owner selection section
            $('#ownerSelectionSection').slideDown();

            // Update labels
            if (ownerType === 'user') {
                $('#ownerTypeLabel').text("{{ __('messages.select_user') }}");
                $('#ownerLabel').text("{{ __('messages.user') }}");
            } else {
                $('#ownerTypeLabel').text("{{ __('messages.select_driver') }}");
                $('#ownerLabel').text("{{ __('messages.driver') }}");
            }

            // Reset and reinitialize Select2
            $('#owner_id').val(null).trigger('change');
            $('#transactionSection').hide();
            $('#submitBtn').hide();
            $('#currentBalance').hide();

            // Initialize Select2 with AJAX
            initializeOwnerSelect2(ownerType);
        } else {
            $('#ownerSelectionSection').slideUp();
            $('#transactionSection').hide();
            $('#submitBtn').hide();
        }
    });

    function initializeOwnerSelect2(type) {
        // Destroy existing Select2 instance
        if ($('#owner_id').hasClass("select2-hidden-accessible")) {
            $('#owner_id').select2('destroy');
        }

        // Clear existing options
        $('#owner_id').empty();

        // Initialize new Select2 with AJAX
        $('#owner_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: "{{ __('messages.search_select_owner') }}",
            allowClear: true,
            ajax: {
                url: "{{ route('wallets.get-owners') }}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        type: type
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            minimumInputLength: 0,
            language: {
                noResults: function() {
                    return "{{ __('messages.no_results_found') }}";
                },
                searching: function() {
                    return "{{ __('messages.searching') }}...";
                },
                inputTooShort: function() {
                    return "{{ __('messages.type_to_search') }}";
                }
            }
        });
    }

    // When owner is selected
    $('#owner_id').on('select2:select', function (e) {
        const data = e.params.data;

        // Set wallet_id
        $('#wallet_id').val(data.wallet_id);

        // Set user_id or driver_id
        if (ownerType === 'user') {
            $('#user_id').val(data.id);
            $('#driver_id').val('');
        } else {
            $('#driver_id').val(data.id);
            $('#user_id').val('');
        }

        // Show current balance
        $('#balanceAmount').text(data.balance);
        $('#currentBalance').slideDown();

        // Show transaction section
        $('#transactionSection').slideDown();
        $('#submitBtn').show();
    });

    $('#owner_id').on('select2:clear', function (e) {
        $('#wallet_id').val('');
        $('#user_id').val('');
        $('#driver_id').val('');
        $('#currentBalance').hide();
        $('#transactionSection').slideUp();
        $('#submitBtn').hide();
    });

    // Ensure only deposit OR withdrawal is filled
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
