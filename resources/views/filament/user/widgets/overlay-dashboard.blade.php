<x-filament::section
    class="shadow-lg/20 relative overflow-hidden rounded-xl p-6 text-white bg-linear-to-r from-violet-600 to-indigo-600">
    {{-- DECORATION --}}
    <div class="absolute right-6 top-6 opacity-20">
        <svg width="120" height="120" viewBox="0 0 120 120" fill="none">
            <path d="M60 0L66 54L120 60L66 66L60 120L54 66L0 60L54 54L60 0Z" fill="white" />
        </svg>
    </div>

    {{-- LOGOUT BUTTON (4) --}}
    <form method="POST" action="{{ filament()->getLogoutUrl() }}" class="absolute right-6 top-6 z-20">
        @csrf
        <button type="submit"
            class="flex h-10 w-10 items-center justify-center rounded-full bg-white/20 hover:bg-white/30 transition"
            title="Logout">
            <x-heroicon-o-arrow-right-on-rectangle class="h-5 w-5 text-white" />
        </button>
    </form>

    {{-- CONTENT --}}
    <div class="relative z-10 grid grid-cols-1 gap-8 lg:grid-cols-2 items-center">

        {{-- LEFT --}}
        <div class="space-y-6">
            <span class="inline-block rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide">
                {{auth()->user()->perusahaan->nama_perusahaan}}
            </span>

            <div>
                <h2 class="text-2xl font-bold leading-tight">
                    Halo👋​
                </h2>
                <h2 class="text-3xl font-bold leading-tight mt-2">
                    {{auth()->user()->name}}
                </h2>
            </div>
        </div>

        {{-- CENTER CLOCK --}}

        <div class="flex flex-col items-center justify-center text-center" x-data="{
        time: '',
        date: '',
        init() {
            this.update()
            setInterval(() => this.update(), 1000)
        },
        update() {
            const now = new Date()

            this.time = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            })

            this.date = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            })
        }
    }">
            <span class="inline-block rounded-full bg-white/20 px-8 py-5 text-xs font-semibold uppercase tracking-wide">
                <div class="text-4xl font-bold tracking-widest" x-text="time"></div>
                <div class="mt-1 text-sm opacity-80" x-text="date"></div>
            </span>

        </div>

    </div>


</x-filament::section>