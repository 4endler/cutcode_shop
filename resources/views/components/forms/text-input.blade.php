@props([
    'isError' => false,
    'classLogin' => 'w-full h-14 px-4 rounded-lg border border-[#A07BF0] bg-white/20 focus:border-pink focus:shadow-[0_0_0_2px_#EC4176] outline-none transition text-white placeholder:text-white text-xxs md:text-xs font-semibold',
    'classOrder' => 'w-full h-16 px-4 rounded-lg border border-body/10 focus:border-pink focus:shadow-[0_0_0_3px_#EC4176] bg-white/5 text-white text-xs shadow-transparent outline-0 transition',
    'classType' => 'login',
])
<input {{$attributes
    ->class([
        '_is-error' => $isError,
        $classType === 'login' ? $classLogin : $classOrder
    ])}}
>