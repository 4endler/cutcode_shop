@props(['item', 'category'=>null])
@if ($category && $category->id == $item->id)
<a href="{{route('catalog', ['category' => $item->slug])}}" class="pointer-events-none bg-purple p-3 sm:p-4 2xl:p-6 rounded-xl text-xxs sm:text-xs lg:text-sm text-white font-semibold">
    {{$item->title}}
</a>
@else
<a href="{{route('catalog', ['category' => $item->slug])}}" class="bg-card p-3 sm:p-4 2xl:p-6 rounded-xl hover:bg-pink text-xxs sm:text-xs lg:text-sm text-white font-semibold">
    {{$item->title}}
</a>
@endif