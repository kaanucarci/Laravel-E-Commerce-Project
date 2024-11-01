@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Message Details</h3>
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
                        <a href="{{route('admin.contact')}}">
                            <div class="text-tiny">Messages</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Message Details</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form class="form-new-product form-style-1">
                    <fieldset class="name">
                        <div class="body-title">Name </div>
                        <input class="flex-grow" type="text"  tabindex="0"
                               value="{{$message->name}}" readonly disabled >
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Phone </div>
                        <input class="flex-grow" type="text" tabindex="0"
                               value="{{$message->phone}}" readonly disabled >
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">E-Mail </div>
                        <input class="flex-grow" type="text" tabindex="0"
                               value="{{$message->email}}" readonly disabled >
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Comment </div>
                        <textarea class="flex-grow" type="text" tabindex="0"
                              readonly disabled >{{$message->comment}}</textarea>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Date </div>
                        <input class="flex-grow" type="text" tabindex="0"
                               value="{{$message->created_at->format('d/m/Y H:i:s')}}" readonly disabled >
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection
