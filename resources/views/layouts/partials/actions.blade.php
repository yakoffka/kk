    <table class="table blue_table table-bordered table-striped">
        <thead>
            <tr>
                <th width="30">№</th>
                <th>Тип</th>
                <th>Дата</th>
                <th>Описание</th>
                @if ( Auth::user()->can('view_orders') )
                    <th>Исполнитель</th>
                @endif
                <th width="30" class="verticalTableHeader ta_c">actions</th>
            </tr>
        </thead>
        @foreach( $actions as $action )
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $action->type }} 
                </td>
                <td>
                    {{-- {{ $action->created_at }} --}}
                    <span title="{{ $action->created_at }}">{{ substr($action->created_at, 0, 10) }}</span>
                </td>
                <td class="description">
                    {{-- {{ $action->description }} --}}
                    {{-- <span title="{{ $action->description }}">{{ str_limit($action->description, 50) }}</span> --}}
                    <span title="{{ $action->description }}">{{ $action->description }}</span>
                </td>
                @if ( Auth::user()->can('view_orders') )
                    <td>
                        {{-- {{ $action->getInitiator->name }} --}}
                        <a 
                            href="{{ route('actions.user', $action->getInitiator) }}" 
                            title="view all actions {{ $action->getInitiator->name }}"
                        >
                            {{ $action->getInitiator->name }}
                        </a>
                    </td>
                @endif

                <td>
                    <a href="{{ route('actions.show', $action) }}" class="btn btn-outline-primary"><i class="fas fa-eye"></i></a>
                </td>

            </tr>
        @endforeach
    </table>
