<div x-data="{ showFetched: true, showAdded: true }" class="p-4 bg-gray-50 rounded-lg mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-bold">Country Management</h2>
        <div class="text-sm text-gray-500">
            Use the form below to search or add new countries.
        </div>
    </div>

    <!-- Collapsible Panels -->
    <div class="space-y-4">
        <!-- Fetched Countries Panel -->
        <div>
            <button
                type="button"
                @click="showFetched = !showFetched"
                class="flex items-center justify-between w-full p-3 bg-white rounded-lg shadow-sm hover:bg-gray-50"
            >
                <span class="font-medium">Fetched Countries (from API)</span>
                <svg x-show="!showFetched" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                <svg x-show="showFetched" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="showFetched" x-collapse class="mt-2 p-4 bg-white rounded-lg shadow">
                <p class="text-sm text-gray-600 mb-2">Countries fetched from RESTCountries API.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach(\App\Models\Country::where('source', 'api')->latest()->take(6)->get() as $country)
                        <div class="p-3 border rounded-lg">
                            <img src="{{ $country->flag }}" class="w-8 h-6 mb-1" />
                            <div class="font-medium">{{ $country->name }}</div>
                            <div class="text-xs text-gray-500">{{ $country->capital }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Added Countries Panel -->
        <div>
            <button
                type="button"
                @click="showAdded = !showAdded"
                class="flex items-center justify-between w-full p-3 bg-white rounded-lg shadow-sm hover:bg-gray-50"
            >
                <span class="font-medium">User-Added Countries</span>
                <svg x-show="!showAdded" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                <svg x-show="showAdded" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div x-show="showAdded" x-collapse class="mt-2 p-4 bg-white rounded-lg shadow">
                <p class="text-sm text-gray-600 mb-2">Countries manually added by users.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach(\App\Models\Country::where('source', 'user_added')->latest()->take(6)->get() as $country)
                        <div class="p-3 border rounded-lg">
                            <img src="{{ $country->flag }}" class="w-8 h-6 mb-1" />
                            <div class="font-medium">{{ $country->name }}</div>
                            <div class="text-xs text-gray-500">{{ $country->capital }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Live Search -->
    <div class="mt-6">
        <div class="relative">
            <input
                x-data="{ query: '', results: [] }"
                x-model="query"
                @input.debounce.300ms="if (query.length > 2) {
                    fetch(`/admin/countries/search?query=` + encodeURIComponent(query))
                        .then(r => r.json())
                        .then(data => results = data)
                } else { results = [] }"
                type="text"
                placeholder="Search country by name..."
                class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
            />
            <div x-show="results.length" class="absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-lg max-h-60 overflow-auto">
                <template x-for="result in results" :key="result.name.common">
                    <div @click="query = result.name.common; results = []" class="p-3 hover:bg-gray-100 cursor-pointer">
                        <div class="font-medium" x-text="result.name.common"></div>
                        <div class="text-sm text-gray-500" x-text="result.capital?.[0] || 'No capital'"></div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
