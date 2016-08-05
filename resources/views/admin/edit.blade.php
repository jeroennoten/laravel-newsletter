@extends('adminlte::page')

@section('title', 'Nieuwe nieuwsbrief')

@section('content_header')
    <h1>Nieuwsbrieven versturen</h1>
@stop

@section('content')
    <form method="post">
        {{ csrf_field() }}
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Nieuwe nieuwsbrief maken</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <input name="subject"
                           value="{{ old('body', $newsletter->subject) }}"
                           class="form-control"
                           placeholder="Onderwerp"
                    >
                </div>
                <div class="form-group no-margin">
                <textarea name="body"
                          class="form-control"
                          style="height: 300px"
                          placeholder="Bericht"
                          id="bodyField"
                >{{ old('body', $newsletter->body) }}</textarea>
                </div>
            </div>
            <div class="box-footer">
                <a href="{{ route('admin.newsletters.index') }}" class="btn btn-danger"><i class="fa fa-remove"></i>
                    Annuleren</a>
                <button type="submit"
                        formaction="{{ $new ? route('admin.newsletters.store') : route('admin.newsletters.update', $newsletter) }}"
                        class="btn btn-default"
                >
                    <i class="fa fa-save"></i> Concept opslaan
                </button>
                <button type="submit"
                        formaction="{{ $new ? route('admin.newsletters.store_and_send') : route('admin.newsletters.update_and_send', $newsletter) }}"
                        class="btn btn-primary"
                >
                    <i class="fa fa-send"></i> Nieuwsbrief verzenden
                </button>
            </div>
        </div>
    </form>
@endsection

@section('js')
    @ckeditor('bodyField')
@endsection