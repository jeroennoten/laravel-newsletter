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
        <div class="box-body no-padding">
            <iframe id="preview"
                    frameborder="0"
                    name="preview"
                    allowtransparency="false"
                    style="background: #fff; height: 400px; width: 100%;"
                    src="{{ route('admin.newsletters.show.body', $newsletter) }}"
            ></iframe>
        </div>
        <div class="box-footer">
            <dl class="no-margin">
                <dt>Verzendlijst</dt>
                <dd>{{ $newsletter->list->name ?? '' }}</dd>
                <dt>Status</dt>
                <dd>Verzonden</dd>
            </dl>
        </div>
    </div>
@endsection

@section('js')
    @ckeditor('bodyField')
@endsection