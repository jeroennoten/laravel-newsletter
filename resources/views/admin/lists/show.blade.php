@extends('adminlte::page')

@section('title', 'Verzendlijsten')

@section('content_header')
    <h1>Verzendlijsten beheren</h1>
@stop

@section('content')
    <a href="{{ route('admin.newsletters.lists.index') }}" class="btn btn-primary margin-bottom">
        <i class="fa fa-arrow-left"></i> Terug
    </a>

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#addOneMember" data-toggle="tab">Enkel lid toevoegen</a></li>
            <li><a href="#addManyMembers" data-toggle="tab">Kommagescheiden lijst toevoegen</a></li>
            <li><a href="#addMembersFile" data-toggle="tab">Excel-bestand uploaden</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="addOneMember">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                <form method="post"
                      action="{{ route('admin.newsletters.lists.members.store', $list) }}"
                      style="display: flex;"
                >
                    {{ csrf_field() }}
                    <input type="text"
                           name="email"
                           value="{{ old('email', '') }}"
                           class="form-control"
                           placeholder="E-mailadres"
                           style="margin-right: 10px;"
                    >
                    <input type="text"
                           name="name"
                           value="{{ old('name', '') }}"
                           class="form-control"
                           placeholder="Naam (optioneel)"
                           style="margin-right: 10px;"
                    >
                    <button type="submit" class="btn btn-success">Toevoegen</button>
                </form>
            </div>
            <div class="tab-pane" id="addManyMembers">
                <add-many-members list-id="{{ $list->getId() }}"></add-many-members>
            </div>
            <div class="tab-pane" id="addMembersFile">
                <add-members-file list-id="{{ $list->getId() }}"></add-members-file>
            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ $list->name }}
                <a href="{{ route('admin.newsletters.lists.edit', $list) }}"
                   class="btn btn-xs btn-default"
                   title="Naam en beschrijving aanpassen"
                   data-toggle="tooltip"
                ><i class="fa fa-edit"></i></a>
                <br>
                <small>{{ $list->description }}</small>
            </h3>
        </div>
        <div class="box-body">
            <member-list list-id="{{ $list->getId() }}"></member-list>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('vendor/newsletter/js/app.js?v3') }}"></script>
@append
