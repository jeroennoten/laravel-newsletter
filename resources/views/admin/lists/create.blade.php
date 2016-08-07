@extends('adminlte::page')

@section('title', 'Verzendlijsten')

@section('content_header')
    <h1>Verzendlijsten beheren</h1>
@stop

@section('content')
    <a href="{{ route('admin.newsletters.lists.index') }}" class="btn btn-xs btn-primary margin-bottom">
        <i class="fa fa-arrow-left"></i> Terug
    </a>
    <form method="post" action="{{ route('admin.newsletters.lists.store') }}">
        {{ csrf_field() }}
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Nieuwe lijst maken</h3>
            </div>
            <div class="box-body">
                @include('newsletter::admin.lists.form')
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Opslaan</button>
                <a href="{{ route('admin.newsletters.lists.index') }}" class="btn btn-danger"><i class="fa fa-times"></i> Annuleren</a>
            </div>
        </div>
    </form>
@endsection
