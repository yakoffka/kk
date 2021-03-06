@extends('layouts.theme_switch')

@section('title', 'create role')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col-xs-12 col-sm-12 col-md-9 breadcrumbs">
            {{ Breadcrumbs::render('roles.create') }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3 d-none d-md-block searchform">{{-- d-none d-md-block - Скрыто на экранах меньше md --}}
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>Create new role</h1>


    <div class="row">

        @include('dashboard.layouts.partials.aside')

        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">


            <form method="POST" action="{{ route('roles.store') }}">
                @csrf

                <h5 class="blue">specify the parameters of the new role:</h5>

                @input(['name' => 'name', 'value' => old('name'), 'required' => 'required'])

                @input(['name' => 'display_name', 'value' => old('display_name'), 'required' => 'required'])

                @input(['name' => 'description', 'value' => old('description'), 'required' => 'required'])

                <h5 class="blue">select permissions for new role:</h5>
                @tablePermissions(['permissions' => $permissions])
                <br>


                <button type="submit" class="btn btn-primary form-control">Create new role!</button>

            </form>
        </div>
    </div>

@endsection
