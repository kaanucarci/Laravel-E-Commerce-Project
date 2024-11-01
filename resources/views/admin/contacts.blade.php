@extends('layouts.admin')
@section('content')
    <style>
        .table-striped>tbody>tr:nth-of-type(odd){
            --bs-table-accent-bg : unset !important;
        }
        .table>tbody>tr:not(.readed){
            background-color : unset !important;
            color: black;
        }
        .table>tbody>tr:not(.readed)>td{
            font-weight: bold;
        }
        .table>tbody>tr.readed{
            background-color : rgba(0, 0, 0, 0.05) !important;
            color: #7b7b7b;
        }
        .table>tbody>tr>td{
           font-weight: normal;
        }
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Messages</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{route('admin.index')}}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Messages</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Search here..." class="" name="name"
                                       tabindex="2" value="" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        @if(Session::has('status'))
                            <div class="alert alert-success text-center">{{Session::get('status')}}</div>
                        @endif
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>E-Mail</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($messages) >= 1)
                                @foreach($messages as $message)
                                    <tr @if($message->is_read == 1) class="readed" @endif>
                                        <td>{{$message->name}}</td>
                                        <td>{{$message->phone}}</td>
                                        <td>{{$message->email}}</td>
                                        <td>{{$message->created_at->format('d/m/Y H:i:s')}}</td>
                                        <td>
                                            <div class="list-icon-function">
                                                <a href="{{route('admin.contact.details', ['id' => $message->id])}}">
                                                    <div class="item eye">
                                                        <i class="icon-eye"></i>
                                                    </div>
                                                </a>
                                                <form action="{{route('admin.contact.delete', ['id' => $message->id])}}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="item text-danger delete">
                                                        <i class="icon-trash-2"></i>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No messages found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="divider"></div>
                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                        {{ $messages->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {

            $('body').on('click', '.delete', function (e) {
                e.preventDefault();
                const form = $(this).parents('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    </script>
@endpush
