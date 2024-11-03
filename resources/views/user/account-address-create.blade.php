@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <form name="checkout-form" action="{{route('user.address.store')}}" method="POST">
                @csrf
                <div class="checkout-form">
                    <div class="billing-info__wrapper">
                        <div class="row">
                            <div class="col-6">
                                <h4>SHIPPING DETAILS</h4>
                            </div>
                            <div class="col-6">
                            </div>
                        </div>

                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="name" required="" value="{{old('name')}}">
                                        <label for="name">Full Name *</label>
                                        <span class="text-danger"></span>
                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="phone" required="" value="{{old('phone')}}">
                                        <label for="phone">Phone Number *</label>
                                        <span class="text-danger"></span>
                                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="zip" required="" value="{{old('zip')}}">
                                        <label for="zip">Pincode *</label>
                                        <span class="text-danger"></span>
                                        @error('zip') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mt-3 mb-3">
                                        <input type="text" class="form-control" name="state" required="" value="{{old('state')}}">
                                        <label for="state">State *</label>
                                        <span class="text-danger"></span>
                                        @error('state') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="city" required="" value="{{old('city')}}">
                                        <label for="city">Town / City *</label>
                                        <span class="text-danger"></span>
                                        @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="address" required="" value="{{old('address')}}">
                                        <label for="address">House no, Building Name *</label>
                                        <span class="text-danger"></span>
                                        @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="locality" required="" value="{{old('locality')}}">
                                        <label for="locality">Road Name, Area, Colony *</label>
                                        <span class="text-danger"></span>
                                        @error('locality') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="landmark" required="" value="{{old('landmark')}}">
                                        <label for="landmark">Landmark *</label>
                                        <span class="text-danger"></span>
                                        @error('landmark') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-checkout" type="submit">SAVE ADDRESS</button>
                                </div>
                            </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
@endsection
