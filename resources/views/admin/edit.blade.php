@extends('adminlte::page')

@section('title', 'Nieuwe nieuwsbrief')

@section('content_header')
    <h1>Nieuwsbrieven versturen</h1>
@stop

@section('content')
    <div class="flex-columns">
        <form method="post" class="editor-container">
            {{ csrf_field() }}
            <div class="box box-primary no-margin">
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
                    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-danger"><i
                                class="fa fa-remove"></i>
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
        <div class="preview-container">
            <h4>Voorbeeld:</h4>
            <iframe id="preview" frameborder="0" name="preview" allowtransparency="false" style="background: #fff"></iframe>
        </div>
    </div>
    <style>
        iframe {
            border: 1px solid #999;
            height: 100%
        }

        .preview-container {
            flex: 1;
            margin-left: 10px;
            display: flex;
            flex-direction: column;
        }

        .editor-container {
            flex: 1;
            margin-right: 10px;
        }

        .flex-columns {
            display: flex;
            flex-wrap: wrap;
        }

        @media (max-width: 1200px) {
            .flex-columns {
                display: block;
            }

            .preview-container {
                margin-left: 0;
            }

            .editor-container {
                margin-right: 0;
            }

            iframe {
                height: 500px;
            }
        }
    </style>
@endsection

@section('js')
    @ckeditor
    <form target="preview" method="post" action="{{ route('admin.newsletters.preview') }}" id="previewForm">
        {{ csrf_field() }}
        <input type="hidden" name="body" id="previewBody">
    </form>
    <script>
        var update = true;
        var outOfSync = false;

        CKEDITOR.replace('bodyField').on('change', function (e) {
            e.editor.updateElement();
            if (update) {
                sync();
            } else {
                outOfSync = true;
            }
        });

        function sync() {
            $('#previewBody').val($('#bodyField').val());
            $('#previewForm').submit();
            outOfSync = false;
            update = false;
            setTimeout(function () {
                update = true;
                if (outOfSync) {
                    sync();
                }
            }, 2000);
        }
    </script>
@endsection