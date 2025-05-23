@extends('layouts.admin')
@section('title')
{{ __('messages.Orders For Games') }}
@endsection


@section('contentheaderactive')
{{ __('messages.Show') }}
@endsection



@section('content')



      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center"> {{ __('messages.Orders For Games') }} </h3>
          <input type="hidden" id="token_search" value="{{csrf_token() }}">


        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
          <div class="col-md-4">

            {{-- <input  type="radio" name="searchbyradio" id="searchbyradio" value="name"> name --}}

            {{-- <input autofocus style="margin-top: 6px !important;" type="text" id="search_by_text" placeholder=" name" class="form-control"> <br> --}}

                      </div>

                          </div>
               <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">

            @if (isset($data) && !$data->isEmpty())

            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <th>{{ __('messages.Status') }}</th>
                    <th>{{ __('messages.selling_price') }}</th>
                    <th>{{ __('messages.number_of_game') }}</th>
                    <th>{{ __('messages.number_of_card') }}</th>
                    <th>{{ __('messages.User') }}</th>
                    <th>{{ __('messages.product') }}</th>
                    <th>{{ __('messages.created_at') }}</th>
                    <th>{{ __('messages.Action') }}</th>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>

                        <td>@if($info->order_status==1) Accepted @elseif($info->order_status==2) Failed @elseif($info->order_status==3) Pending @endif</td>
                        <td>{{ $info->price }}</td>
                        <td>{{ $info->number_of_game }}</td>
                        <td>
                            {{ $info->binNumber->bin_number ?? 'N/A' }}
                        </td>
                        <td>{{ $info->user->name }}</td>
                        <td>{{ $info->product->name_ar }}</td>
                        <td>{{ $info->created_at }}</td>

                        <td>
                            <a href="{{ route('orders.charge', $info->id) }}" class="btn btn-sm btn-primary">Charge</a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="openNotificationModal({{ $info->user->id }})">Send Notification</a>
                            <form action="{{ route('orders.destroy', $info->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            {{ $data->links() }}

            @else
            <div class="alert alert-danger">
                {{ __('messages.No_data') }}
            </div>
            @endif

        </div>

<!-- Notification Modal -->
<div class="modal fade" id="sendNotificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('orders.notification.send') }}" method="POST">
      @csrf
      <input type="hidden" name="user_id" id="user_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send Notification</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="form-group">
            <label for="body">Body</label>
            <textarea class="form-control" id="body" name="body" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Send Notification</button>
        </div>
      </div>
    </form>
  </div>
</div>


      </div>

        </div>

</div>

@endsection

@section('script')
<script>
function openNotificationModal(userId) {
    $('#user_id').val(userId);
    $('#sendNotificationModal').modal('show');
}
</script>
@endsection


