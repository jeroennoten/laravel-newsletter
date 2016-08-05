@extends('adminlte::page')

@section('title', 'Nieuwe nieuwsbrief')

@section('content_header')
    <h1>Nieuwsbrieven versturen</h1>
@stop

@section('content')
    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-primary margin-bottom">
        <i class="fa fa-arrow-left"></i> Terug
    </a>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $newsletter['subject'] }}</h3>
        </div>
        <div class="box-body">
            {!! $newsletter['body'] !!}
        </div>
        <div class="box-footer">
            <dl class="no-margin">
                <dt>Status</dt>
                <dl class="no-margin">Verzonden</dl>
            </dl>
        </div>
    </div>
@endsection

@section('js')
    @ckeditor('bodyField')
@endsection