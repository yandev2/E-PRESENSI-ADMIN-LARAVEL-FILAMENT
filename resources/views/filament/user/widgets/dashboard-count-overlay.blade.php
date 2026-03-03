<x-filament-widgets::widget>
    <style>
        .filament-slider-container {
            overflow: hidden;
            position: relative;
            width: 100%;
            cursor: grab;
            /* Ubah cursor agar user tahu bisa ditarik */
        }

        .filament-slider-container:active {
            cursor: grabbing;
        }

        .filament-slider-track {
            display: flex;
            width: max-content;
            /* Kita gunakan variabel CSS agar bisa dikontrol JS jika perlu */
            animation: filament-loop-scroll 150s linear infinite;
            will-change: transform;
        }

        /* Pause saat hover ATAU saat sedang di-drag */
        .filament-slider-container.is-dragging .filament-slider-track,
        .filament-slider-track:hover {
            animation-play-state: paused;
        }

        @keyframes filament-loop-scroll {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        .slider-mask {
            pointer-events: none;
            position: absolute;
            inset: 0;
            z-index: 10;
            /*  background: linear-gradient(90deg, rgba(249, 250, 251, 1) 0%, rgba(249, 250, 251, 0) 10%, rgba(249, 250, 251, 0) 90%, rgba(249, 250, 251, 1) 100%);*/
        }

        .dark .slider-mask {
            /*  background: linear-gradient(90deg, rgba(9, 9, 11, 1) 0%, rgba(9, 9, 11, 0) 10%, rgba(9, 9, 11, 0) 90%, rgba(9, 9, 11, 1) 100%);*/
        }

        /* Hilangkan scrollbar manual karena kita pakai drag */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <div class="filament-slider-container py-4 no-scrollbar" x-data="{ 
            isDown: false, 
            startX: 0, 
            scrollLeft: 0,
            handleMouseDown(e) {
                this.isDown = true;
                this.$el.classList.add('is-dragging');
                this.startX = e.pageX - this.$el.offsetLeft;
                this.scrollLeft = this.$el.scrollLeft;
            },
            handleMouseUp() {
                this.isDown = false;
                this.$el.classList.remove('is-dragging');
            },
            handleMouseMove(e) {
                if(!this.isDown) return;
                e.preventDefault();
                const x = e.pageX - this.$el.offsetLeft;
                const walk = (x - this.startX) * 2; // Kecepatan drag
                this.$el.scrollLeft = this.scrollLeft - walk;
            }
        }" @mousedown="handleMouseDown($event)" @mouseleave="handleMouseUp()" @mouseup="handleMouseUp()"
        @mousemove="handleMouseMove($event)" style="overflow-x: auto;">
        <div class="slider-mask"></div>

        <div class="filament-slider-track">
            @foreach($displayItems as $widget)
            <div class="px-3 w-75 shrink-0 select-none"> {{-- select-none agar teks tidak ter-highlight saat di-drag
                --}}
                <div
                    class="flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-white/5 dark:bg-gray-900 transition-all hover:scale-105">
                    
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full {{ $widget['bg'] }} {{ $widget['color'] }}">
                        <x-filament::icon icon="{{ $widget['icon'] }}" class="h-9 w-9" />
                    </div>

                    <div class="flex-1 min-w-0">
                           <p class="text-xs font-light text-gray-400 dark:text-gray-500 uppercase truncate">
                            {{ $widget['title'] }}
                        </p>
                        <h4 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white leading-tight">
                            {{ $widget['val'] }}
                        </h4>
                        <p class="text-sm font-semibold text-gray-400 dark:text-gray-500 uppercase truncate">
                            {{ $widget['label'] }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>