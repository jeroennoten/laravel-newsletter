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
                        <th>Aanmaakdatum</th>
                        <th>Bewerkingsdatum</th>
                        <th>Verzenddatum</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($newsletters as $newsletter)
                        <tr style="cursor: pointer;"
                            onclick="location.href = '{{ route('admin.newsletters.show', $newsletter) }}';">
                            <td>
                                @if($newsletter['subject'])
                                    {{ $newsletter['subject'] }}
                                @else
                                    <em>Geen onderwerp</em>
                                @endif
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

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Aangemelde e-mailadressen</h3>
        </div>
        <div class="box-body">
            {{ implode(', ', $emails) }}
        </div>
    </div>
@endsection

@section('js')
    @ckeditor('bodyField')
@endsection