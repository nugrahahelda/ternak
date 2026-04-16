<?php

namespace App\Providers;

use App\Models\FeedRecord;
use App\Observers\FeedRecordObserver;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS URL scheme when behind proxy
        if ($this->app->environment('production') || request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        FeedRecord::observe(FeedRecordObserver::class);

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_START,
            fn (): string => Blade::render(<<<'HTML'
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
                <meta name="mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-status-bar-style" content="default">
                <meta name="apple-mobile-web-app-title" content="MyFarm">
                <meta name="theme-color" content="#f59e0b">
                <link rel="manifest" href="/manifest.json">
                <link rel="apple-touch-icon" href="/icon-192.svg">
                <script defer src="https://umami.syofyanzuhad.dev/script.js" data-website-id="58b64bc6-ab8a-4442-be39-d202888d197a"></script>
            HTML)
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn (): string => Blade::render(<<<'HTML'
                <style>
                    #pwa-install-prompt {
                        position: fixed;
                        bottom: 1rem;
                        right: 1rem;
                        z-index: 50;
                    }
                    #pwa-install-prompt.hidden {
                        display: none;
                    }
                    #pwa-install-card {
                        background: white;
                        border-radius: 0.5rem;
                        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                        padding: 1rem;
                        max-width: 24rem;
                        border: 1px solid #e5e7eb;
                    }
                    .dark #pwa-install-card {
                        background: #1f2937;
                        border-color: #374151;
                    }
                    #pwa-card-content {
                        display: flex;
                        align-items: flex-start;
                        gap: 0.75rem;
                    }
                    #pwa-icon {
                        flex-shrink: 0;
                        width: 2.5rem;
                        height: 2.5rem;
                        color: #f59e0b;
                    }
                    #pwa-card-body {
                        flex: 1;
                    }
                    #pwa-title {
                        font-weight: 600;
                        color: #111827;
                        margin: 0;
                    }
                    .dark #pwa-title {
                        color: white;
                    }
                    #pwa-description {
                        font-size: 0.875rem;
                        color: #4b5563;
                        margin-top: 0.25rem;
                    }
                    .dark #pwa-description {
                        color: #9ca3af;
                    }
                    #pwa-buttons {
                        display: flex;
                        gap: 0.5rem;
                        margin-top: 0.75rem;
                    }
                    #pwa-install-btn {
                        padding: 0.5rem 1rem;
                        background: #f59e0b;
                        color: white;
                        font-size: 0.875rem;
                        font-weight: 500;
                        border-radius: 0.5rem;
                        border: none;
                        cursor: pointer;
                        transition: background 0.2s;
                    }
                    #pwa-install-btn:hover {
                        background: #d97706;
                    }
                    #pwa-dismiss-btn {
                        padding: 0.5rem 1rem;
                        background: #e5e7eb;
                        color: #374151;
                        font-size: 0.875rem;
                        font-weight: 500;
                        border-radius: 0.5rem;
                        border: none;
                        cursor: pointer;
                        transition: background 0.2s;
                    }
                    #pwa-dismiss-btn:hover {
                        background: #d1d5db;
                    }
                    .dark #pwa-dismiss-btn {
                        background: #374151;
                        color: #d1d5db;
                    }
                    .dark #pwa-dismiss-btn:hover {
                        background: #4b5563;
                    }
                    #pwa-close-btn {
                        flex-shrink: 0;
                        color: #9ca3af;
                        background: none;
                        border: none;
                        cursor: pointer;
                        padding: 0;
                        transition: color 0.2s;
                    }
                    #pwa-close-btn:hover {
                        color: #4b5563;
                    }
                    .dark #pwa-close-btn:hover {
                        color: #d1d5db;
                    }
                    #pwa-close-icon {
                        width: 1.25rem;
                        height: 1.25rem;
                    }
                </style>

                <!-- PWA Install Button -->
                <div id="pwa-install-prompt" class="hidden">
                    <div id="pwa-install-card">
                        <div id="pwa-card-content">
                            <svg id="pwa-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.75 2.75a.75.75 0 00-1.5 0v8.614L6.295 8.235a.75.75 0 10-1.09 1.03l4.25 4.5a.75.75 0 001.09 0l4.25-4.5a.75.75 0 00-1.09-1.03l-2.955 3.129V2.75z" />
                                <path d="M3.5 12.75a.75.75 0 00-1.5 0v2.5A2.75 2.75 0 004.75 18h10.5A2.75 2.75 0 0018 15.25v-2.5a.75.75 0 00-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5z" />
                            </svg>
                            <div id="pwa-card-body">
                                <h3 id="pwa-title">Install MyFarm</h3>
                                <p id="pwa-description">Install aplikasi untuk akses lebih cepat dan bisa digunakan offline</p>
                                <div id="pwa-buttons">
                                    <button id="pwa-install-btn">Install</button>
                                    <button id="pwa-dismiss-btn">Nanti</button>
                                </div>
                            </div>
                            <button id="pwa-close-btn">
                                <svg id="pwa-close-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <script>
                    let deferredPrompt;
                    const installPrompt = document.getElementById('pwa-install-prompt');
                    const installBtn = document.getElementById('pwa-install-btn');
                    const dismissBtn = document.getElementById('pwa-dismiss-btn');
                    const closeBtn = document.getElementById('pwa-close-btn');

                    // Service Worker Registration - DISABLED temporarily
                    // Unregister existing service workers
                    if ('serviceWorker' in navigator) {
                        navigator.serviceWorker.getRegistrations().then(function(registrations) {
                            for(let registration of registrations) {
                                registration.unregister().then(function(success) {
                                    console.log('ServiceWorker unregistered:', success);
                                });
                            }
                        });
                    }

                    // if ('serviceWorker' in navigator) {
                    //     window.addEventListener('load', function() {
                    //         navigator.serviceWorker.register('/sw.js')
                    //             .then(function(registration) {
                    //                 console.log('ServiceWorker registered:', registration.scope);
                    //             })
                    //             .catch(function(error) {
                    //                 console.log('ServiceWorker registration failed:', error);
                    //             });
                    //     });
                    // }

                    // PWA Install Prompt
                    window.addEventListener('beforeinstallprompt', (e) => {
                        e.preventDefault();
                        deferredPrompt = e;

                        // Check if user has dismissed before
                        const dismissed = localStorage.getItem('pwa-install-dismissed');
                        if (!dismissed) {
                            installPrompt.classList.remove('hidden');
                        }
                    });

                    // Install button click
                    installBtn.addEventListener('click', async () => {
                        if (!deferredPrompt) return;

                        deferredPrompt.prompt();
                        const { outcome } = await deferredPrompt.userChoice;

                        if (outcome === 'accepted') {
                            console.log('PWA installed');
                        }

                        deferredPrompt = null;
                        installPrompt.classList.add('hidden');
                    });

                    // Dismiss button click
                    dismissBtn.addEventListener('click', () => {
                        localStorage.setItem('pwa-install-dismissed', 'true');
                        installPrompt.classList.add('hidden');
                    });

                    // Close button click
                    closeBtn.addEventListener('click', () => {
                        installPrompt.classList.add('hidden');
                    });

                    // Hide prompt if already installed
                    window.addEventListener('appinstalled', () => {
                        console.log('PWA installed');
                        installPrompt.classList.add('hidden');
                    });
                </script>
            HTML)
        );
    }
}
