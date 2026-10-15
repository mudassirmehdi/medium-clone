<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-5">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Posts') }}
            </h2>

            <x-href-button href="{{ route('post.create') }}">
                 {{ __('Create a New Post') }}
                
            </x-href-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg">

                <div class="relative overflow-x-auto bg-white border border-gray-200 shadow-sm sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-4">Image</th>
                                <th scope="col" class="px-6 py-4">Title</th>
                                <th scope="col" class="px-6 py-4">Category</th>
                                <th scope="col" class="px-6 py-4">Published Date</th>
                                <th scope="col" class="px-6 py-4">Likes</th>
                                <th scope="col" class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            @forelse($posts as $post)
                                <tr class="bg-white hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        @if ($post->imageUrl())
                                            <img src="{{ $post->imageUrl() }}" alt="Web development"
                                                class="w-16 h-12 rounded-md object-cover border border-gray-200">
                                        @else
                                            <div class="flex items-center justify-center">
                                                <svg class="h-12 w-12 text-slate-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5" d="M4 5h16v14H4zM4 15l5-5 4 4 2-2 5 5" />
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="font-semibold text-gray-900 hover:text-blue-600">
                                            {{ $post->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                            {{ $post->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <x-clap-button :post="$post" />
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('post.edit', $post->slug) }}"
                                                class="font-medium text-blue-600 hover:underline">Edit</a>


                                            <form class="inline-block" action="{{ route('post.delete', $post) }}"
                                                method="post">
                                                @csrf
                                                {{-- @method('delete') --}}
                                                <button type="submit"
                                                    class="font-medium text-red-600 hover:underline">Delete</button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty

                                <tr class="bg-white hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">No post published yet...</td>

                                </tr>
                            @endforelse


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
