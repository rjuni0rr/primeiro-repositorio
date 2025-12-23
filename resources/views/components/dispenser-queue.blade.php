<a href="#" class="flex gap-2 rounded-xl p-2" style="background-color: {{ $queue->colors->prefix_bg_color }}">

    {{-- prefix --}}
    <div class="text-center font-mono bg-slate-100 rounded-xl p-1" style="background-color: {{ $queue->colors->prefix_bg_color }}; color: {{ $queue->colors->prefix_text_color }};">
        <p class="text-8xl px-4 font-bold text-slate-700">{{ $queue->prefix }}</p>
    </div>

    {{-- serice and desk --}}
    <div class="bg-slate-100 w-full rounded-xl p-3 " style="background-color: {{ $queue->colors->number_bg_color }}; color: {{ $queue->colors->number_text_color }};">
        <p class="text-3xl font-bold text-slate-700">{{ $queue->service }}</p>
        <p class="text-2xl font-bold text-slate-400">{{ $queue->desk }}</p>
    </div>

</a>
