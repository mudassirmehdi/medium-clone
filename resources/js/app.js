
import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {


    Alpine.data('followListModal', (followersUrl, followingUrl, isAuthenticated, currentUserId) => ({
        isOpen: false,
        activeTab: 'followers',
        users: [],
        nextPageUrl: null,
        followersCount: 0,
        followingCount: 0,
        loading: false,
        loadingMore: false,
        error: '',
        isAuthenticated,
        currentUserId,

        async open(tab = 'followers') {
            this.isOpen = true;
            this.error = '';

            await this.loadCounts();
            await this.switchTab(tab);
        },

        async loadCounts() {
            try {
                const [followersResponse, followingResponse] = await Promise.all([
                    fetch(followersUrl, {
                        headers: { Accept: 'application/json' },
                    }),
                    fetch(followingUrl, {
                        headers: { Accept: 'application/json' },
                    }),
                ]);

                if (!followersResponse.ok || !followingResponse.ok) {
                    throw new Error('Unable to load follower counts.');
                }

                const [followersData, followingData] = await Promise.all([
                    followersResponse.json(),
                    followingResponse.json(),
                ]);

                this.followersCount = followersData.total;
                this.followingCount = followingData.total;
            } catch (error) {
                console.error(error);
            }
        },

        async switchTab(tab) {
            this.activeTab = tab;
            this.users = [];
            this.nextPageUrl = null;
            this.error = '';

            await this.fetchUsers(
                tab === 'followers' ? followersUrl : followingUrl
            );
        },

        async fetchUsers(url, append = false) {
            if (!url) return;

            this.loading = !append;
            this.loadingMore = append;
            this.error = '';

            try {
                const response = await fetch(url, {
                    headers: { Accept: 'application/json' },
                });

                if (!response.ok) {
                    throw new Error('Unable to load users.');
                }

                const result = await response.json();

                const people = result.data.map(person => ({
                    ...person,
                    followLoading: false,
                }));

                this.users = append
                    ? [...this.users, ...people]
                    : people;

                this.nextPageUrl = result.next_page_url;

                if (this.activeTab === 'followers') {
                    this.followersCount = result.total;
                } else {
                    this.followingCount = result.total;
                }
            } catch (error) {
                console.error(error);
                this.error = error.message || 'Something went wrong.';
            } finally {
                this.loading = false;
                this.loadingMore = false;
            }
        },

        async loadMore() {
            if (!this.nextPageUrl || this.loadingMore) return;

            await this.fetchUsers(this.nextPageUrl, true);
        },

        async toggleFollow(person) {
            if (!this.isAuthenticated || person.followLoading) return;

            person.followLoading = true;
            this.error = '';

            try {
                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute('content');

                const response = await fetch(person.follow_url, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken ?? '',
                    },
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Unable to update follow status.');
                }

                person.is_following = result.is_following;

                // Notify the main profile card to refresh its counts/state.
                window.dispatchEvent(new CustomEvent('follow-status-updated', {
                    detail: {
                        userId: person.id,
                        isFollowing: result.is_following,
                    },
                }));
            } catch (error) {
                console.error('Follow action failed:', error);
                this.error = error.message || 'Unable to update follow status.';
            } finally {
                person.followLoading = false;
            }
        },
        
        close() {
            this.isOpen = false;
        },
    }));


});

Alpine.start();




