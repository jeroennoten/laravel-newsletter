@extends('adminlte::page')

@section('title', 'Nieuwsbrieven')

@section('content_header')
    <h1>Nieuwsbrieven versturen</h1>
@stop

@section('content')
    <a href="{{ route('admin.newsletters.create') }}" class="btn btn-success margin-bottom">
        <i class="fa fa-plus"></i> Nieuwe nieuwsbrief maken
    </a>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Nieuwsbrieven</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table no-margin table-hover">
                    <thead>
                    <tr>
                        <th>Onderwerp</th>
                        <th>Verzendlijst</th>
                        <th>Aanmaakdatum</th>
                        <th>Bewerkingsdatum</th>
                        <th>Verzenddatum</th>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($newsletters as $newsletter)
                        <tr style="cursor: pointer;"
                            onclick="location.href = '{{ route('admin.newsletters.show', $newsletter) }}';"
                        >
                            <td>
                                @if($newsletter['subject'])
                                    {{ $newsletter['subject'] }}
                                @else
                                    <em>Geen onderwerp</em>
                                @endif
                            </td>
                            <td>
                                {{ $newsletter->list->name or '' }}
                            </td>
                            <td>
                                @if($newsletter['created_at'])
                                    @timestamp($newsletter['created_at'])
                                @else
                                    <em>Onbekend</em>
                                @endif
                            </td>
                            <td>
                                @if($newsletter['updated_at'])
                                    @timestamp($newsletter['updated_at'])
                                @else
                                    <em>Onbekend</em>
                                @endif
                            </td>
                            <td>
                                @if($newsletter['sent_at'])
                                    @timestamp($newsletter['sent_at'])
                                @else
                                    <em>Nog niet verzonden</em>
                                @endif
                            </td>
                            <td>
                                <form method="post"
                                      action="{{ route('admin.newsletters.destroy', $newsletter) }}"
                                      style="display: inline"
                                >
                                    {{ method_field('delete') }}
                                    {{ csrf_field() }}
                                    <button id="deleteNewsletter{{ $newsletter->id }}Button"
                                            class="btn btn-danger btn-xs"
                                            onclick="return confirmDeleteNewsletter(event)"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @ckeditor('bodyField')
    <script>
        function confirmDeleteNewsletter(event) {
            event.stopPropagation();
            return confirm('Weet je zeker dat je deze nieuwsbrief wilt verwijderen? Deze actie kan niet ongedaan gemaakt worden.');
        }
    </script>
@endsection