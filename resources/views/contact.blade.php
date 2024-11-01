@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="contact-us container">
            <div class="mw-930">
                <h2 class="page-title">CONTACT US</h2>
            </div>
        </section>

        <hr class="mt-2 text-secondary " />
        <div class="mb-4 pb-4"></div>

        <section class="contact-us container">
            <div class="mw-930">
                <div class="contact-us__form">
                    <form name="contact-us-form" class="needs-validation" novalidate="" action="{{route('contact.send')}}" method="POST">
                        @csrf
                        <h3 class="mb-5">Get In Touch</h3>
                        @if(Session::has('status'))
                            <div class="alert alert-success">
                                {{session('status')}}
                            </div>
                        @endif
                        <div class="form-floating my-4">
                            <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Name *" required="">
                            <label for="contact_us_name">Name *</label>
                            <span class="text-danger">
                                @error('name')
                                {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="form-floating my-4">
                            <input type="text" class="form-control" name="phone" maxlength="10" value="{{old('phone')}}" placeholder="Phone *" required="">
                            <label for="contact_us_name">Phone *</label>
                            <span class="text-danger">
                                @error('phone')
                                  {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="form-floating my-4">
                            <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="Email address *" required="">
                            <label for="contact_us_name">Email address *</label>
                            <span class="text-danger">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="my-4">
              <textarea class="form-control form-control_gray" name="comment" placeholder="Your Message" cols="30"
                        rows="8" required="">{{old('comment')}}</textarea>
                            <span class="text-danger">
                                @error('comment')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="my-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection
