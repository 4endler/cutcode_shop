<nav class="hidden 2xl:flex gap-8">
    @foreach ($menu->all() as $item)
        <a href="{{$item->link()}}" class="@if($item->isActive()) text-pink pointer-events-none @else text-white hover:text-pink @endif  font-bold">{{$item->label()}}</a>
    @endforeach
</nav>
