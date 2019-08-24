@extends('layouts.app')

@section('title', 'actions user')

@section('content')

    <div class="row searchform_breadcrumbs">
        <div class="col col-sm-9">
            {{ Breadcrumbs::render('users.actions', $user) }}
        </div>
        <div class="col col-sm-3">
            @include('layouts.partials.searchform')
        </div>
    </div>


    <h1>show user {{ $user->name }}</h1>


    <div class="row">

        @include('layouts.partials.aside')

        <div class="col col-sm-10 pr-0">

            <h2>{{ $user->name }} info:</h2>
            <table class="blue_table overflow_x_auto">
                <tr>
                    <th>id</th>
                    <th>img</th>
                    <th>name</th>
                    <th>email</th>
                    <th>roles</th>
                    <th>permissions</th>
                    <th>created</th>
                    <th>updated</th>
                    <th class="actions2">actions</th>
                </tr>

                <tr>
                    <td>{{ $user->id }}</td>
                    <td><img src="{{ asset('storage') }}/images/default/user_default.png" alt="no image" width="75px"></td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><a href="#roles">{{ $user->roles->count() }}
                    </a></td>
                    <td><a href="#perms">
                        <?php
                            $num_permissions = 0;
                            foreach ($permissions as $permission) {
                                if ( $user->can($permission->name) ) { $num_permissions++; }
                            }
                            echo $num_permissions;
                        ?>
                    </a></td>
                    <td>{{ $user->created_at ?? '-' }}</td>
                    <td>{{ $user->updated_at ?? '-' }}</td>
                    <td>

                        @permission('edit_users')
                            <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-outline-success">
                                <i class="fas fa-pen-nib"></i>
                            </a>
                        @else
                            @if ( Auth::user()->id == $user->id )
                                <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-outline-success">
                                    <i class="fas fa-pen-nib"></i>
                                </a>
                            @endif
                        @endpermission


                        @if( auth()->user()->can('delete_users') or Auth::user()->id == $user->id )
                        {{-- @permission('delete_users') --}}
                            {{-- <form action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST" class="del_btn">
                                @csrf

                                @method("DELETE")

                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form> --}}
                            @modalConfirmDestroy([
                                'btn_class' => 'btn btn-outline-danger del_btn',
                                'cssId' => 'delele_',
                                // 'type_item' => 'пользователя'
                                'item' => $user,
                                'action' => route('users.destroy', ['user' => $user->id]),
                            ])

                        @endif                
                        {{-- @endpermission --}}

                    </td>
                </tr>
            </table><br><br><br>

            
            @permission('view_roles')
                <h2 id="roles">Roles of {{ $user->name }}:</h2>
                @foreach ($user->roles as $role)
                    @if($loop->last){{ $loop->iteration }} <a href="{{ route('roles.show', ['role' => $role->id]) }}">{{ $role->display_name }}</a>.
                    @else{{ $loop->iteration }} <a href="{{ route('roles.show', ['role' => $role->id]) }}">{{ $role->display_name }}</a>, 
                    @endif
                @endforeach
            @endpermission


            @permission('view_permissions')
                <h2 id="perms">Permissions for {{ $user->name }}:</h2>
                @tablePermissions(['permissions' => $permissions, 'user' => $user])
                <br><br><br>
            @endpermission
        </div>
    </div>

@endsection