<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-5">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Eid Post') }}
            </h2>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form method="POST" action="{{  route('post.update', $post->id) }}" enctype="multipart/form-data">
                        @csrf

                        @method('put')

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Post Title')" />

                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title', $post->title)" placeholder="Enter your post title" required autofocus />

                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Slug -->
                        {{-- <div class="mt-4">
                            <x-input-label for="slug" :value="__('Slug')" />

                            <x-text-input id="slug" class="block mt-1 w-full" type="text" name="slug"
                                :value="old('slug')" placeholder="my-awesome-blog-post" required />

                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div> --}}

                        <!-- Category -->
                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('Category')" />

                            <select id="category_id" name="category_id"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                <option value="">Select a category</option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <!-- Featured Image -->
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Featured Image')" />

                            <input id="image" type="file" name="image" accept="image/*"
                                class="block mt-1 w-full text-sm text-gray-700
                   file:mr-4 file:py-2 file:px-4
                   file:rounded-md file:border-0
                   file:bg-gray-100 file:text-gray-700
                   hover:file:bg-gray-200" />

                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Excerpt -->
                        {{-- <div class="mt-4">
                            <x-input-label for="excerpt" :value="__('Excerpt')" />

                            <x-input-textarea id="excerpt" name="excerpt" rows="3"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                placeholder="Write a short summary of your blog post...">{{ old('excerpt') }}</x-input-textarea>

                            <x-input-error :messages="$errors->get('excerpt')" class="mt-2" />
                        </div> --}}

                        <!-- Content -->
                        <div class="mt-4">
                            <x-input-label for="content" :value="__('Post Content')" />

                            <textarea id="content" name="content" rows="10"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                placeholder="Write your blog post content here..." required>{{ old('content', $post->content) }}</textarea>

                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        {{-- Published At --}}

         

                        <!-- Status -->
                        {{-- <div class="mt-4">
                            <x-input-label for="status" :value="__('Publication Status')" />

                            <select id="status" name="status"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                <option value="draft" @selected(old('status', 'draft') === 'draft')>
                                    Draft
                                </option>

                                <option value="published" @selected(old('status') === 'published')>
                                    Published
                                </option>
                            </select>

                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div> --}}

                        <!-- Actions -->
                        <div class="flex items-center justify-end mt-6 gap-4">
                            {{-- <a href=""
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a> --}}

                            <x-primary-button>
                                {{ __('Update Post') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
