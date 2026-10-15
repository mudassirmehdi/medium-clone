@props(['user'])

<div class="px-6 pb-6 pt-8 text-center" x-data="{
    following: {{ $user->isFollowedBy(auth()->user()) ? 'true' : 'false' }},
    followersCount: {{ $user->followers()->count() }},
    followingCount: {{ $user->following()->count() }},
    followLoading: false,

    async follow() {
        if (this.followLoading) return;

        this.followLoading = true;

        try {
            const response = await axios.post('/follow/{{ $user->id }}');

            this.following = response.data.following;
            this.followersCount = response.data.followerCount;

            if (response.data.followingCount !== undefined) {
                this.followingCount = response.data.followingCount;
            }
        } catch (error) {
            console.error(
                error.response?.data || error
            );
        } finally {
            this.followLoading = false;
        }
    }
}">
    {{ $slot }}
</div>
