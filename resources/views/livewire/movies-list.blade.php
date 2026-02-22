<div class="w-full">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">Filmy</h1>

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex-1">
                <input type="text" wire:model.live="search" placeholder="Szukaj filmów..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
            </div>

            <div>
                <select wire:model.live="perPage"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="6">6 na stronie</option>
                    <option value="12">12 na stronie</option>
                    <option value="24">24 na stronie</option>
                    <option value="48">48 na stronie</option>
                </select>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            <button wire:click="setSortBy('release_date')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $sortBy === 'release_date' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-white' }}">
                Data wydania
                @if ($sortBy === 'release_date')
                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                @endif
            </button>
            <button wire:click="setSortBy('vote_average')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $sortBy === 'vote_average' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-white' }}">
                Ocena
                @if ($sortBy === 'vote_average')
                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                @endif
            </button>
            <button wire:click="setSortBy('popularity')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $sortBy === 'popularity' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-white' }}">
                Popularność
                @if ($sortBy === 'popularity')
                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                @endif
            </button>
            <button wire:click="setSortBy('title')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $sortBy === 'title' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-white' }}">
                Tytuł
                @if ($sortBy === 'title')
                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                @endif
            </button>
        </div>
    </div>

    <div class="mb-8">
        @if ($movies->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($movies as $movie)
                    <div
                        class="group bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="relative h-80 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                            @if ($movie->poster_path)
                                <img src="https://image.tmdb.org/t/p/w500{{ $movie->poster_path }}"
                                    alt="{{ $movie->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4.5-4.5 3 3 2-2 4.5 4.5z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <div
                                class="absolute top-2 right-2 bg-yellow-500 text-gray-900 px-2 py-1 rounded-lg font-bold text-sm">
                                {{ number_format($movie->vote_average, 1) }}
                            </div>
                        </div>

                        <div class="p-4">
                            <h3
                                class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400">
                                {{ $movie->title }}
                            </h3>

                            @if ($movie->release_date)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    {{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}
                                </p>
                            @endif

                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4 line-clamp-3">
                                {{ $movie->overview ?: 'Brak opisu' }}
                            </p>

                            <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.95-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    {{ number_format($movie->popularity, 0) }}
                                </span>
                                @if ($movie->vote_count)
                                    <span>{{ $movie->vote_count }} głosów</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $movies->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 4v16m10-16v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nie znaleziono filmów</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    @if ($search)
                        Spróbuj zmienić zapytanie wyszukiwania
                    @else
                        Nie ma dostępnych filmów
                    @endif
                </p>
            </div>
        @endif
    </div>

    <div wire:loading class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                <span class="ml-3 text-gray-900 dark:text-white">Ładowanie...</span>
            </div>
        </div>
    </div>
</div>
