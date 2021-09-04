@extends('layouts.app')


@section('title')
    Blog Operation
@endsection



@section('content')

    @component('layouts.components.modal', [
    'id' => 'create',
    'size'=>'modal-lg',
    'title' => 'Create Blog',
    'body' => 'blog.create',
    'submitBtnId' => 'createSubmitBtn',
    'submitBtn' => 'Create',
    'closeBtn' => 'Close',
    'footer'=> 'true',
    'form' => 'true',
    'formAction' => 'blog.create',
    'formHeader' => 'multipart/form-data',
    'formId' => 'createForm',
    'formMethod' => 'POST',
    ])
    @endcomponent

    @component('layouts.components.modal', [
    'id' => 'edit',
    'size'=>'modal-lg',
    'title' => 'Edit Blog',
    'body' => 'blog.edit',
    'submitBtnId' => 'editSubmitBtn',
    'submitBtn' => 'Edit',
    'closeBtn' => 'Close',
    'footer'=> 'true',
    'form' => 'true',
    'formAction' => 'blog.update',
    'formHeader' => 'multipart/form-data',
    'formId' => 'updateForm',
    'formMethod' => 'POST',
    ])
    @endcomponent

    @component('layouts.components.modal', [
    'id' => 'delete',
    'size'=>'modal-sm',
    'title' => 'Delete Blog',
    'body' => 'blog.delete',
    'submitBtnId' => 'deleteSubmitBtn',
    'submitBtn' => 'Delete',
    'closeBtn' => 'Close',
    'footer'=> 'true',
    'form' => 'true',
    'formAction' => 'blog.delete',
    'formHeader' => 'multipart/form-data',
    'formId' => 'deleteForm',
    'formMethod' => 'POST',
    ])
    @endcomponent


    <div class="container py-3">
        <div class="row">
            <div class="col-12 text-right my-2">
                <div class="btn btn-primary shadow-2" data-toggle="modal" data-target="#create">
                    <i class="mdi mdi-plus mdi-22px cursor-pointer " id="showCreateModal"></i> Create Blog
                </div>
            </div>
            <div class="col-12 text-left my-2">
                @component('layouts.components.filter_form_001',
                    [
                        'body' => 'blog.filter',
                        'form' => 'true',
                        'formAction' => 'blog.show',
                        'formId' => 'filterForm',
                        'formMethod' => 'POST',
                        'searchBtn' => 'search',
                        'resetBtn' => 'Reset',
                        ])
                @endcomponent
            </div>
            {{--filter --}}

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
    @include('blog.script')
@endsection
