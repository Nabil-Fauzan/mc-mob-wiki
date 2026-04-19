<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
    @for($i = 0; $i < 8; $i++)
        <div class="glass-card rounded-[2rem] overflow-hidden">
            <div class="aspect-[4/5] bg-white/5 skeleton"></div>
            <div class="p-6 space-y-4">
                <div class="h-6 w-3/4 skeleton"></div>
                <div class="flex gap-2">
                    <div class="h-4 w-12 skeleton rounded-full"></div>
                    <div class="h-4 w-16 skeleton rounded-full"></div>
                </div>
                <div class="space-y-2">
                    <div class="h-3 w-full skeleton"></div>
                    <div class="h-3 w-5/6 skeleton"></div>
                    <div class="h-3 w-4/6 skeleton"></div>
                </div>
                <div class="pt-4 border-t border-white/5 flex justify-between">
                    <div class="h-8 w-8 skeleton rounded-lg"></div>
                    <div class="h-4 w-12 skeleton"></div>
                </div>
            </div>
        </div>
    @endfor
</div>
