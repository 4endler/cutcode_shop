@extends('layouts.app')
@section('title', 'Избранное')
@section('content')
<!-- Breadcrumbs -->
<ul class="breadcrumbs flex flex-wrap gap-y-1 gap-x-4 mb-6">
    <li><a href="{{route('home')}}" class="text-body hover:text-pink text-xs">Главная</a></li>
    <li><span class="text-body text-xs">Избранное</span></li>
</ul>

<section>
    <!-- Section heading -->
    <h1 class="mb-8 text-lg lg:text-[42px] font-black">Избранное</h1>

    <!-- Message -->
    @if($items->isEmpty())
        <div class="py-3 px-6 rounded-lg bg-pink text-white">Корзина пуста</div>
    @else
        <div class="lg:hidden py-3 px-6 rounded-lg bg-pink text-white">Таблицу можно пролистать вправо →</div>
    

        @foreach ($items as $item)
        <tr>
            <td scope="row" class="py-4 px-4 md:px-6 rounded-l-2xl bg-card">
                <div class="flex flex-col lg:flex-row min-w-[200px] gap-2 lg:gap-6">
                    <div class="shrink-0 overflow-hidden w-[64px] lg:w-[84px] h-[64px] lg:h-[84px] rounded-2xl">
                        <img src="{{$item->product->makeThumbnail('345x320')}}" class="object-cover w-full h-full" alt="{{$item->product->title}}">
                    </div>
                    <div class="py-3">
                        <h4 class="text-xs sm:text-sm xl:text-md font-bold"><a href="{{route('product', $item->product)}}" class="inline-block text-white hover:text-pink">{{$item->product->title}}</a></h4>
                       
                    </div>
                </div>
            </td>
            <td class="py-4 px-4 md:px-6 bg-card">
                <div class="font-medium whitespace-nowrap">{{$item->product->price}}</div>
            </td>
         

 
        </tr> 
        @endforeach


    @endif

</section>
@endsection