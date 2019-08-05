@component('mail::message')
# Создан новый товар "{{ $product->name }}"

Товар создан пользователем "{{ $user->name }}".

Для просмотра перейдите по ссылке ниже.

@component('mail::button', ['url' => route('products.show', ['product' => $product->id])])
show
@endcomponent

{{ config('app.name') }}
@endcomponent
