<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container mx-auto">
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('products.create') }}"
                                class="mb-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Add new product</a>
                        @endif

                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Name</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Price (USD)</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Price (EUR)</th>

                                    @if (auth()->user()->is_admin)
                                        <th class="px-6 py-3 bg-gray-50 text-left">
                                            Action
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                @forelse ($products as $product)
                                    <tr class="bg-white">
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ $product->name }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ $product->price }}</td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ $product->price_eur }}</td>
                                        @if (auth()->user()->is_admin)
                                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900 inline-flex">
                                                <a href="{{ route('products.edit', $product) }}"
                                                    class="mb-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                    Edit</a>
                                                <form action="{{ route('products.destroy', $product) }}" method="post" class="ml-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button onclick="return confirm('Are you sure ?')">Delete</x-danger-button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ __('no products found') }}</td>
                                    </tr>
                                @endforelse
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
