import './bootstrap';
import collapse from '@alpinejs/collapse';

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(collapse);

    window.Alpine.store('radio', {
        audio: null,
        audioPlaying: false,
        playerOpen: true,
        boundAudio: null,
        bind(audio) {
            if (!audio) return;

            if (this.boundAudio === audio) {
                this.audio = audio;
                this.sync();
                return;
            }

            this.audio = audio;
            this.boundAudio = audio;

            const sync = () => this.sync();

            ['play', 'playing', 'pause', 'ended', 'stalled', 'emptied', 'waiting'].forEach((eventName) => {
                audio.addEventListener(eventName, sync);
            });

            this.sync();
        },
        sync() {
            this.audioPlaying = !!this.audio && !this.audio.paused && !this.audio.ended;
        },
        async start() {
            if (!this.audio || !this.audio.src) return;

            this.playerOpen = true;

            try {
                await this.audio.play();
                this.audioPlaying = true;
            } catch (error) {
                console.error('Live audio playback failed:', error);
            }
        },
        async toggle() {
            if (!this.audio || !this.audio.src) return;

            if (this.audio.paused) {
                await this.start();
                return;
            }

            this.audio.pause();
            this.audioPlaying = false;
        },
        closePlayer() {
            this.playerOpen = false;
        },
    });
});

if (!window.__glowLivewireNavigateInstalled) {
    window.__glowLivewireNavigateInstalled = true;

    document.addEventListener('click', (event) => {
        if (event.defaultPrevented || event.button !== 0) return;
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

        const anchor = event.target.closest('a[href]');
        if (!anchor) return;
        const isManagedByNavigate = Array.from(anchor.attributes).some(({ name }) => {
            return name === 'x-navigate' || name.startsWith('wire:navigate') || name.startsWith('x-navigate.');
        });

        if (isManagedByNavigate || anchor.hasAttribute('download')) return;
        if (anchor.hasAttribute('data-no-livewire-nav') || anchor.closest('[data-no-livewire-nav]')) return;
        if (anchor.target && anchor.target.toLowerCase() !== '_self') return;

        const href = anchor.getAttribute('href') || '';
        if (!href || href === '#' || href.startsWith('#')) return;
        if (href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) return;

        let url;

        try {
            url = new URL(anchor.href, window.location.href);
        } catch (error) {
            return;
        }

        if (!['http:', 'https:'].includes(url.protocol)) return;
        if (url.origin !== window.location.origin) return;
        if (/^\/(?:dashboard(?:\/|$)|admin(?:\/|$))/.test(url.pathname)) return;

        const lastSegment = url.pathname.split('/').pop() || '';
        if (/\.(pdf|zip|csv|xls|xlsx|doc|docx|ppt|pptx|mp3|wav|aac|ogg|jpg|jpeg|png|gif|webp|svg|mp4|mov|avi|mkv|json|xml|txt)$/i.test(lastSegment)) return;

        const isHashOnlyNavigation = url.pathname === window.location.pathname
            && url.search === window.location.search
            && !!url.hash;

        if (isHashOnlyNavigation) return;
        if (!window.Livewire || typeof window.Livewire.navigate !== 'function') return;

        event.preventDefault();
        window.Livewire.navigate(`${url.pathname}${url.search}${url.hash}`);
    });
}

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch((error) => {
            console.error('Service worker registration failed:', error);
        });
    });
}
