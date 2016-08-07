@extends('adminlte::page')

@section('title', 'Verzendlijsten')

@section('content_header')
    <h1>Verzendlijsten beheren</h1>
@stop

@section('content')
    <a href="{{ route('admin.newsletters.lists.create') }}" class="btn btn-success margin-bottom">
        <i class="fa fa-plus"></i> Nieuwe lijst maken
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
                        <th>Naam</th>
                        <th>Beschrijving</th>
                        <th>Aantal leden</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lists as $list)
                        <tr style="cursor: pointer;"
                            onclick="location.href = '{{ route('admin.newsletters.lists.show', $list) }}';">
                            <td>{{ $list->name }}</td>
                            <td>{{ $list->description }}</td>
                            <td>{{ $list->membersCount }}</td>
                            <td>
                                <form method="post"
                                      action="{{ route('admin.newsletters.lists.destroy', $list) }}"
                                      style="display: inline"
                                      onsubmit="return confirm('Weet je zeker dat je de lijst \'{{ $list->name }}\' wilt verwijderen? Deze actie kan niet ongedaan gemaakt worden.')"
                                >
                                    {{ method_field('delete') }}
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-danger btn-xs">
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
