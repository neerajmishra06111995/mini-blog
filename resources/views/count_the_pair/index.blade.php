@extends('layouts.app')


@section('title')
    Blog Operation
@endsection



@section('content')

    @component('layouts.components.modal', [
    'id' => 'create',
    'size'=>'modal-lg',
    'title' => 'Create Count Pairs',
    'body' => 'count_the_pair.create',
    'submitBtnId' => 'createSubmitBtn',
    'submitBtn' => 'Create',
    'closeBtn' => 'Close',
    'footer'=> 'true',
    'form' => 'true',
    'formAction' => 'blog.create-the-pair',
    'formHeader' => 'multipart/form-data',
    'formId' => 'createForm',
    'formMethod' => 'POST',
    ])
    @endcomponent




    <div class="container py-3">
        <div class="row">
            <div class="col-12 text-right my-2">
                <div class="btn btn-primary shadow-2" data-toggle="modal" data-target="#create">
                    <i class="mdi mdi-plus mdi-22px cursor-pointer " id="showCreateModal"></i> Create Pairs
                </div>
            </div>

        </div>
        <div class="row my-2">
            <div class="col-12">
                <div id="show"></div>
            </div>
        </div>
    </div>


@endsection


@section('script')
    @include('layouts.components.filter_form_001_script')
    @include('count_the_pair.script')
@endsection
