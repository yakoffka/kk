{{-- 

@modalForm([
  'item' => $item,
  'button_class' => 'primary',
  'modalCssId' => 'change_task_comment_' . $task->id,
  'modal_title' => 'Комментарий исполнителя',
  'text_button' => $task->comment_slave,
  'describe' => 'describe rrrrrr',
  'action' => {{ route('cart.change-item', ['product' => $product->id]) }},
  // 'multipart' => ' enctype="multipart/form-data"',
  'multipart' => '',
  'method' => 'POST',
  // 'method' => 'PATCH',
  // 'method' => 'DELETE',
  'submit_text' => 'применить',
])

 --}}


<!-- Button trigger modal -->
<button type="button" class="btn btn-outline-{{ $class ?? 'primary' }}" data-toggle="modal" data-target="#{{ $modalCssId }}">
    {!! $text_button !!}
</button>

<!-- Modal -->
<div class="modal fade" id="{{ $modalCssId }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modalCssId }}Label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="{{ $modalCssId }}Label">{{ $modal_title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="describe"> {{ $describe }}</div>
        <form method="POST" action="{{ $action }}"{{ $multipart }}>

          @csrf

          @method('{{ $method }}')

          {{-- @input(['name' => 'quantity', 'type' => 'number', 'value' => $text_button])--}}
          @input(['name' => 'comment_slave', 'value' => old('comment_slave')])

          <button type="submit" class="btn btn-primary form-control">{{ $submit_text }}</button>

        </form>
      </div>

      {{-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> --}}

    </div>
  </div>
</div>
