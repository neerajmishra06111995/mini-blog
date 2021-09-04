@extends('layouts.app')


@section('title')
    Blog Operation
@endsection

@section('content')
    <div class="container py-3">
        <div class="row">
            <div class="col-12 text-right my-2">
                <a class="btn btn-info" href="{{ route('blog.send-email') }}" >Send Email</a>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-12">
                <div id="show">
                    @if( isset($outputMsg['message']) && !empty($outputMsg['message']) )
                        <div class="alert alert-{{$outputMsg['type']}}">
                            <strong>{{$outputMsg['type']}}!</strong> {{$outputMsg['message']}}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <strong>Click on the send email button</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection



