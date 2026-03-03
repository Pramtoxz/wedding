<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh bg-gradient-to-br from-pink-50 via-white to-purple-50">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-pink-600 to-purple-600 p-12 flex-col justify-between relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0,0 L100,0 L100,100 L0,100 Z" fill="url(#grid)" />
                    <defs>
                        <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <circle cx="5" cy="5" r="1" fill="white" />
                        </pattern>
                    </defs>
                </svg>
            </div>
            
            <div class="relative z-10">
                <Link :href="home()" class="flex items-center gap-3 text-white">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <div>
                        <h1 class="text-2xl font-bold">Wedding Invitation</h1>
                        <p class="text-sm text-white/80">Digital Invitation Platform</p>
                    </div>
                </Link>
            </div>

            <div class="relative z-10 space-y-6">
                <h2 class="text-4xl font-bold text-white leading-tight">
                    Create Beautiful Digital Wedding Invitations
                </h2>
                <p class="text-lg text-white/90">
                    Modern, elegant, and fully customizable wedding invitation platform. Share your special day with loved ones.
                </p>
                
                <div class="grid grid-cols-2 gap-4 pt-6">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-3xl font-bold text-white mb-1">100+</div>
                        <div class="text-sm text-white/80">Templates</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-3xl font-bold text-white mb-1">1000+</div>
                        <div class="text-sm text-white/80">Happy Couples</div>
                    </div>
                </div>
            </div>

            <div class="relative z-10">
                <p class="text-sm text-white/70">Built with ❤️ by</p>
                <a 
                    href="https://portfolio-pramudito.vercel.app/" 
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 text-white hover:text-white/80 transition-colors mt-2"
                >
                    <div class="w-8 h-8 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center font-bold border border-white/30">
                        P
                    </div>
                    <div>
                        <div class="font-semibold">Pramudito Metra</div>
                        <div class="text-xs text-white/70">Software & UAV Engineer</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="flex-1 flex items-center justify-center p-6 md:p-10">
            <div class="w-full max-w-md">
                <div class="flex flex-col gap-8">
                    <!-- Mobile Logo -->
                    <div class="flex flex-col items-center gap-4 lg:hidden">
                        <Link :href="home()" class="flex items-center gap-3">
                            <svg class="w-10 h-10 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            <div>
                                <h1 class="text-xl font-bold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">Wedding Invitation</h1>
                            </div>
                        </Link>
                    </div>

                    <div class="space-y-2 text-center lg:text-left">
                        <h1 class="text-2xl font-bold text-gray-900">{{ title }}</h1>
                        <p class="text-sm text-gray-600">{{ description }}</p>
                    </div>
                    
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
