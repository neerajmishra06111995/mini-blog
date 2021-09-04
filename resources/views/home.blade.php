@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="container" style="height: 100vh !important;">
        <div class="form-row justify-content-center align-content-center h-100">
            <div class="form-group col-md-4">
                <div class="list-group">
                    <a href="{{ route('blog.index') }}" class="list-group-item list-group-item-action">CRUD Operation</a>
                    <a href="{{ route('blog.preview.show') }}" class="list-group-item list-group-item-action">CRUD Show Previews</a>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                       class="list-group-item list-group-item-action font-weight-bold text-danger ">Logout </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
