@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Addresses</h2>
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-9">
                    @if(session('status'))
                        <div class="alert alert-success">
                            {{session('status')}}
                        </div>
                    @endif
                    <div class="page-content my-account__address">
                        <div class="row">
                            <div class="col-6">
                                <p class="notice">The following addresses will be used on the checkout page by default.</p>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{route('user.address.create')}}" class="btn btn-sm btn-info">Add New</a>
                            </div>
                        </div>
                        <div class="my-account__address-list row">
                            <h5>Shipping Address</h5>

                            @foreach($addresses as $address)
                            <div class="my-account__address-item col-md-6">
                                <div class="my-account__address-item__title">
                                    <h5>{{$address->name}} <input type="radio" @if($address->default_address == 1) checked @endif class="form-check-input" name="default_address" id="" value="{{$address->id}}"> </h5>
                                    <a href="{{route('user.address.edit', $address->id)}}">Edit</a>
                                </div>
                                <div class="my-account__address-item__detail">
                                    <p>{{$address->address}}</p>
                                    <p>{{$address->locality}}</p>
                                    <p>{{$address->city}}/{{$address->state}}</p>
                                    <p>{{$address->country}}</p>
                                    <p>{{$address->zip}}</p>
                                    <br>
                                    <p>Mobile : {{$address->phone}}</p>
                                </div>
                            </div>
                            <hr>
                            @endforeach

                            <div class="divider">
                                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                                    {{$addresses->withQueryString()->links('pagination::bootstrap-5')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection


@push('scripts')
    <script>
        $(document).on('click', '.form-check-input', function() {
            var id = $(this).val();

            $.ajax({
                url: "{{route('user.address.default',  '')}}/" + id,
                type: "PUT",
                data: {
                    _token: "{{csrf_token()}}"
                },
                error: function(error) {
                    Swal.fire({
                        title: "Error",
                        icon: "error",
                        confirmButtonText: "Ok"
                    });
                }
            });
        });
    </script>
@endpush
