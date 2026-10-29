@props(['recipe', 'featured' => false])

<article {{ $attributes->class(['group relative overflow-hidden rounded-[2rem] border border-white/10 bg-night/80 shadow-2xl shadow-black/10 transition duration-500 hover:-translate-y-2 hover:border-white/20', 'md:col-span-2 lg:col-span-1' => $featured]) }} data-reveal>
    <a href="{{ route('recipes.show', $recipe) }}" class="block" aria-label="Ver receta: {{ $recipe->title }}">
        <div class="relative overflow-hidden {{ $featured ? 'aspect-[16/11]' : 'aspect-[4/3]' }}">
            <img src="{{ asset($recipe->image_path) }}" alt="Página ilustrada de {{ $recipe->title }}" class="size-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/15 to-transparent"></div>
            <span class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-ink/80 py-1.5 pl-1.5 pr-3 text-[10px] font-black uppercase tracking-[0.16em] text-cream backdrop-blur-md">
                <img src="{{ asset($recipe->animeSeries->image_path) }}" alt="" class="size-7 rounded-full object-cover" aria-hidden="true">
                {{ $recipe->animeSeries->name }}
            </span>
            @if ($recipe->is_featured)
                <span class="absolute right-4 top-4 rounded-full bg-gold px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.16em] text-ink">Favorita</span>
            @endif
        </div>
        <div class="relative p-6">
            <div class="absolute inset-x-6 top-0 h-1 -translate-y-1/2 rounded-full" style="background: linear-gradient(90deg, {{ $recipe->animeSeries->color_from }}, {{ $recipe->animeSeries->color_to }})"></div>
            <h3 class="text-balance text-xl font-black leading-tight text-cream transition group-hover:text-gold">{{ $recipe->title }}</h3>
            <p class="mt-2 line-clamp-2 text-sm leading-6 text-cream/55">{{ $recipe->subtitle ?: $recipe->description }}</p>
            <div class="mt-5 flex items-center gap-4 border-t border-white/10 pt-4 text-xs font-bold text-cream/50">
                <span class="inline-flex items-center gap-1.5"><span class="text-coral">◷</span> {{ $recipe->prep_minutes + $recipe->cook_minutes }} min</span>
                <span class="inline-flex items-center gap-1.5"><span class="text-mint">●</span> {{ ucfirst($recipe->difficulty) }}</span>
                <span class="ml-auto text-cream transition group-hover:translate-x-1">Ver receta →</span>
            </div>
        </div>
    </a>
</article>
