@props(['post'])

@auth
    <button x-data="{
        hasClapped: {{ auth()->user()->hasClapped($post) ? 'true' : 'false' }},
        count: {{ $post->claps()->count() }},
        clap() {
            axios.post('/clap/{{ $post->id }}')
                .then(response => {
                    this.hasClapped = !this.hasClapped;
                    this.count = response.data.clapsCount;
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }" class="flex gap-2 item-center justify-center" @click="clap()">
        <template x-if="!hasClapped">
            <svg class="h-5 w-5 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path
                    d="M12 20.5s-7.5-4.6-9.2-9.4C1.6 7.7 3.7 4.5 7 4.5c2 0 3.4 1.1 5 3 1.6-1.9 3-3 5-3 3.3 0 5.4 3.2 4.2 6.6-1.7 4.8-9.2 9.4-9.2 9.4z" />
            </svg>
        </template>
        <template x-if="hasClapped">
            <svg class="h-5 w-5 text-accent" viewBox="0 0 24 24" fill="#ED1D24" stroke="#ED1D24" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path
                    d="M12 20.5s-7.5-4.6-9.2-9.4C1.6 7.7 3.7 4.5 7 4.5c2 0 3.4 1.1 5 3 1.6-1.9 3-3 5-3 3.3 0 5.4 3.2 4.2 6.6-1.7 4.8-9.2 9.4-9.2 9.4z" />
            </svg>
        </template>
        <span x-text="count"></span>
    </button>


@endauth
