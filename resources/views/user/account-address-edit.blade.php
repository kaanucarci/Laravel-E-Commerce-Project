@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <form name="checkout-form" action="{{route('user.address.update')}}" method="POST">
                @method('PUT')
                @csrf
                <input type="hidden" name="address_id" value="{{ $address->id }}">
                <div class="checkout-form">
                    <div class="billing-info__wrapper">
                        <div class="row">
                            <div class="col-6">
                                <h4>EDİT SHIPPING DETAILS</h4>
                            </div>
                            <div class="col-6">
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="name" required="" value="{{$address->name}}">
                                    <label for="name">Full Name *</label>
                                    <span class="text-danger"></span>
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="phone" required="" value="{{$address->phone}}">
                                    <label for="phone">Phone Number *</label>
                                    <span class="text-danger"></span>
                                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="zip" required="" value="{{$address->zip}}">
                                    <label for="zip">Pincode *</label>
                                    <span class="text-danger"></span>
                                    @error('zip') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating mt-3 mb-3">
                                    <input type="text" class="form-control" name="state" required="" value="{{$address->state}}">
                                    <label for="state">State *</label>
                                    <span class="text-danger"></span>
                                    @error('state') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="city" required="" value="{{$address->city}}">
                                    <label for="city">Town / City *</label>
                                    <span class="text-danger"></span>
                                    @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="address" required="" value="{{$address->address}}">
                                    <label for="address">House no, Building Name *</label>
                                    <span class="text-danger"></span>
                                    @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="locality" required="" value="{{$address->locality}}">
                                    <label for="locality">Road Name, Area, Colony *</label>
                                    <span class="text-danger"></span>
                                    @error('locality') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" name="landmark" required="" value="{{$address->landmark}}">
                                    <label for="landmark">Landmark *</label>
                                    <span class="text-danger"></span>
                                    @error('landmark') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-checkout" type="submit">UPDATE ADDRESS</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
@endsection
