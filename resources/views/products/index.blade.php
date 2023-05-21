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
                        <a href="{{ route('products.create') }}" class="mb-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Add new product</a>
                        @endif
  
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Name</th>
                                    <th class="px-4 py-2">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr>
                                    <td class="border px-4 py-2">Product 1</td>
                                    <td class="border px-4 py-2">$10.00</td>
                                </tr>
                                <tr>
                                    <td class="border px-4 py-2">Product 2</td>
                                    <td class="border px-4 py-2">$15.00</td>
                                </tr> -->

                                @forelse ($products as $product)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $product->name }}</td>
                                        <td class="border px-4 py-2">{{ $product->price }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="border px-4 py-2">{{ __('no products found') }}</td>
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
