@props(['width' => 'w-[45%]', 'link' => '#'])

<a href="{{$link}}" class="p-2 border border-white rounded {{$width}} 
        transition-all duration-300 ease-in-out 
        hover:bg-white hover:text-gray-800">
    {{$slot}}
</a>