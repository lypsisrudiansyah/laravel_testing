<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="min-w-full align-middle">
                        <form action="{{route('products.update', $product)}}" method="PUT" enctype="multipart/form-data">
                            @csrf

                            <!-- Name -->
                            <div>
                                <x-label for="name" :value="__('Name')" />

                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="$product->name" required autofocus />
                            </div>
                            <div class="mt-4">
                                <x-label for="price" :value="__('Price')" />

                                <x-text-input id="price" class="block mt-1 w-full" type="text" name="price" :value="$product->price" required autofocus />
                            </div>

                            <div class="flex items-center mt-4">
                                <x-primary-button>
                                    {{ __('Save') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
