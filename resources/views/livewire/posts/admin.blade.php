<div class="mt-12">


    <!-- Action buttons -->
    <button wire:click.prevent="create" class="rounded bg-blue-500 py-2 px-4 font-bold text-white hover:bg-blue-700">
        {{ __('Add new post') }}
    </button>
    <button wire:click.prevent="deleteSelected" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
        class="@if ($bulkDisabled) opacity-50 @endif rounded bg-red-500 py-2 px-4 font-bold text-white hover:bg-red-700">
        {{ __('Delete selected') }}
    </button>

    <table class="mt-6 table min-w-full border-white">


        <!-- Table head -->
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-sm leading-4 tracking-wider"></th>
                <th class="px-6 py-3 text-left text-sm leading-4 tracking-wider">{{ __('Id') }}</th>
                <th class="px-6 py-3 text-left text-sm leading-4 tracking-wider">{{ __('Category') }}</th>
                <th class="px-6 py-3 text-left text-sm leading-4 tracking-wider">{{ __('Language') }}</th>
                <th class="px-6 py-3 text-left text-sm leading-4 tracking-wider">{{ __('Title') }}</th>
                <th class="px-6 py-3 text-left text-sm leading-4 tracking-wider">{{ __('Start date') }}</th>
                <th class="px-6 py-3 text-left text-sm leading-4 tracking-wider">{{ __('End date') }}</th>
                <th class="px-6 py-3 text-left text-sm leading-4 tracking-wider"></th>
                <th class="px-6 py-3 text-left text-sm leading-4 tracking-wider"></th>
            </tr>
        </thead>


        <!-- Table body -->
        <tbody>
            @forelse ($posts as $post)
                @if ($post->translations->count() === 0)
                    {{-- Do not show post without any translation --}}
                @else
                    <tr>
                        <td colspan="9">
                            @foreach ($post->translations as $key => $translation)
                    <tr
                        class="dark:bordergray-600 transition duration-300 ease-in-out hover:bg-gray-100 dark:hover:bg-gray-600">
                        <td class="py34 whitespace-no-wrap border-b border-white px-6 text-sm leading-5">
                            <input type="checkbox" wire:model="bulkSelected" value="{{ $translation->id }}">
                        </td>
                        <td class="whitespace-no-wrap border-b border-white px-6 py-3 text-sm leading-5">
                            {{ $post->id }}
                        </td>
                        <td class="whitespace-no-wrap border-b border-white px-6 py-3 text-sm leading-5">
                            @if ($post->category->translations->first())
                                {{ $post->category->translations->first()->name }}
                            @endif
                        </td>
                        <td class="whitespace-no-wrap border-b border-white px-6 py-3 text-sm leading-5">
                            {{ $translation->locale }}
                        </td>
                        <td class="whitespace-no-wrap border-b border-white px-6 py-3 text-sm leading-5">
                            {{ $translation->title }}
                        </td>
                        <td class="whitespace-no-wrap border-b border-white px-6 py-3 text-sm leading-5">
                            @if ($translation->start)
                                {{ \Carbon\Carbon::createFromTimeStamp(strtotime($translation->start))->isoFormat('LL') }}
                            @endif
                        </td>
                        <td class="whitespace-no-wrap border-b border-white px-6 py-3 text-sm leading-5">
                            @if ($translation->stop)
                                {{ \Carbon\Carbon::createFromTimeStamp(strtotime($translation->stop))->isoFormat('LL') }}
                            @endif
                        </td>


                        <!-- Row buttons -->
                        <td class="whitespace-no-wrap border-b border-white py-2.5 text-sm leading-5">
                            @if ($translation->start < \Carbon\Carbon::now() && $translation->start !== null)
                                @if ($translation->stop > \Carbon\Carbon::now() || $translation->stop === null)
                                    <button
                                        class="focus:shadow-outline-gray inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-1 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out focus:border-gray-900 focus:outline-none disabled:opacity-25"
                                        wire:click.prevent="stop({{ $translation->id }})"
                                        onclick="confirm('Do you want to end the publication of this post?') || event.stopImmediatePropagation()">
                                        {{ __('Stop') }}
                                    </button>
                                @endif
                            @endif
                        </td>
                        <td class="whitespace-no-wrap border-b border-white py-2.5 text-sm leading-5">
                            <button
                                class="focus:shadow-outline-gray inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-1 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:border-gray-900 focus:outline-none active:bg-gray-900 disabled:opacity-25"
                                wire:click.prevent="edit({{ $translation->id }})"> {{ __('Edit') }}
                            </button>
                        </td>


                    </tr>
                @endforeach
            @endif
        @empty
            <tr>
                <td colspan="9">
                    {{ __('No posts found.') }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <hr>


    <!-- Pagination links -->
    {{ $posts->links() }}


    <!-- Edit modal -->
    <div
        class="@if (!$showModal) hidden @endif fixed left-0 bottom-0 flex h-full w-full items-center justify-center bg-gray-800 bg-opacity-50">
        <div class="h-5/6 w-3/5 overflow-scroll rounded-lg bg-white">
            <form wire:submit.prevent="save" class="w-ful">
                <div class="flex flex-col items-start p-4">
                    <div class="flex w-full items-center bg-white pb-4">
                        @if ($createTranslation === true)
                            <div class="text-lg font-medium text-gray-900">{{ __('Add translation to post') }}</div>
                        @else
                            <div class="text-lg font-medium text-gray-900">
                                {{ $postId ? __('Edit post') : __('Create new post') }}</div>
                        @endif
                        <svg wire:click="close" class="ml-auto h-6 w-6 cursor-pointer fill-current text-gray-700"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z" />
                        </svg>
                    </div>
                    <div class="required flex space-x-12 py-2">
                        <livewire:category-selectbox key="{{ Str::random() }}" :categorySelected="$categoryId" />
                        <!-- Use the key to keep track of component that are in a loop -->
                        @error('categoryId')
                            <p class="mt-2 text-sm text-red-600" id="category-error">{{ $message }}</p>
                        @enderror
                        <livewire:language-selectbox key="{{ Str::random() }}" :locale="$locale" :available="$localesAvailable" />
                        <!-- Use the key to keep track of component that are in a loop -->
                        @error('locale')
                            <p class="mt-2 text-sm text-red-600" id="locale-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full py-2">
                        <label class="block text-sm font-medium text-gray-700">
                            {{ __('Title') }} <span class="text-red-600">*</span>
                        </label>
                        <input wire:model="title"
                            class="mt-2 w-full rounded-lg border border-gray-400 py-2 pl-2 pr-4 text-sm text-xl focus:border-blue-400 focus:outline-none sm:text-base" />
                        @error('title')
                            <p class="mt-2 text-sm text-red-600" id="title-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full py-2">
                        <label class="block text-sm font-medium text-gray-700">
                            {{ __('Slug') }} <span class="text-red-600">*</span>
                        </label>
                        <input wire:model.lazy="post.slug"
                            class="mt-2 w-full rounded-lg border border-gray-400 py-2 pl-2 pr-4 text-sm text-xl focus:border-blue-400 focus:outline-none sm:text-base" />
                        @error('post.slug')
                            <p class="mt-2 text-sm text-red-600" id="slug-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full py-2">
                        <x-textarea wire:model.defer="post.excerpt" label="{{ __('Intro') }}" placeholder="" />
                        @error('post.excerpt')
                            <p class="mt-2 text-sm text-red-600" id="excerpt-error">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Content --- WYSIWYG editor (Trix editor) -->
                    <livewire:trix-editor :value="$post['content']" />
                    @error('post.content')
                        <p class="mt-2 text-sm text-red-600" id="locale-error">{{ $message }}</p>
                    @enderror


                    <!-- File upload -->
                    <label class="form-label mt-6">{{ __('Image') }}</label>
                    <div class="w-full">
                        @if ($image === null)
                            <div class="mb-2 h-48 w-64"> {!! $media !!} </div>
                        @else
                            <!-- Preview image -->
                            {{-- Make sure that that object cover class w and h is 4 by 3 proportion as images will later be cropped in 4 by 3 proportions --}}
                            <img src="{{ $image->temporaryUrl() }}" class="mb-2 h-48 w-64 object-cover">
                        @endif
                        <div x-data="{ isUploading: false, progress: 5 }" x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false; progress = 5"
                            x-on:livewire-upload-error="isUploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <!-- File Input -->
                            <input type="file" wire:model="image">
                            <!-- Progress Bar -->
                            <div x-show.transition="isUploading"
                                class="flex-start my-6 flex h-4 w-64 overflow-hidden rounded bg-gray-100 font-sans text-xs font-medium">
                                <progress max="100" x-bind:value="progress"
                                    class="flex h-full items-baseline justify-center overflow-hidden break-all text-white"
                                    x-bind:style="`width:${progress}%`"></progress>
                            </div>
                        </div>
                        @error('image')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>


                    <!-- Date pickers -->
                    <div class="flex space-x-12">
                        <div class="z-50 my-6 flex-auto">
                            <x-datetime-picker label="{{ __('Start of publication') }}"
                                placeholder="{{ __('Select a date') }}" wire:model="start" :without-time="true"
                                display-format="DD-MM-YYYY" />
                        </div>
                        <div class="z-50 my-6 flex-auto">
                            <x-datetime-picker label="{{ __('End of publication') }}"
                                placeholder="{{ __('Select a date') }}" wire:model="stop" :without-time="true"
                                display-format="DD-MM-YYYY" />
                        </div>
                    </div>
                    @if ($start < \Carbon\Carbon::now() && $start !== null)
                        @if ($stop > \Carbon\Carbon::now() || $stop === null)
                            <div class="mb-3 text-right">
                                {{ __('Warning') . ': ' . __('post will be published immeditely!') }}
                            </div>
                        @endif
                    @else
                        <div class="mb-3 text-right">
                        </div>
                    @endif

                    <div class="ml-auto mt-6">

                        @if ($createTranslation === true)
                            <button class="rounded bg-black py-2 px-4 font-bold text-white hover:bg-gray-700"
                                type="submit">{{ $postId ? __('Add Translation') : __('Save') }}
                            </button>
                        @else
                            <button class="rounded bg-black py-2 px-4 font-bold text-white hover:bg-gray-700"
                                type="submit">{{ $postId ? __('Update') : __('Save') }}
                            </button>
                        @endif
                        <button class="rounded bg-gray-500 py-2 px-4 font-bold text-white" wire:click="close"
                            type="button" data-dismiss="modal">{{ __('Close') }}
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>


    <!--- Scripts body section, loaded in layouts at the end of the page just befor the </body> tag -->
    @section('scripts_body')
        <script>
            {{-- console.log('scripts body section executes'); --}}
        </script>
    @endsection

</div>