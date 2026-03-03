<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Heart, Calendar, Image, Users, MessageSquare } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

interface Stats {
    total_weddings: number;
    total_events: number;
    total_galleries: number;
    total_guests: number;
    total_wishes: number;
}

interface RecentWedding {
    id: number;
    bride_name: string;
    groom_name: string;
    wedding_date: string;
    slug: string;
    guests_count: number;
    wishes_count: number;
}

const props = defineProps<{
    stats: Stats;
    recentWeddings: RecentWedding[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
];

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Dashboard</h2>
                <p class="text-muted-foreground">Selamat datang di Wedding Invitation Dashboard</p>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                <div class="rounded-xl border bg-card p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Total Undangan</p>
                            <p class="text-3xl font-bold mt-2">{{ stats.total_weddings }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-pink-100 dark:bg-pink-900/20 flex items-center justify-center">
                            <Heart class="h-6 w-6 text-pink-600 dark:text-pink-400" />
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border bg-card p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Total Acara</p>
                            <p class="text-3xl font-bold mt-2">{{ stats.total_events }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                            <Calendar class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border bg-card p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Total Galeri</p>
                            <p class="text-3xl font-bold mt-2">{{ stats.total_galleries }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                            <Image class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border bg-card p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Total Tamu</p>
                            <p class="text-3xl font-bold mt-2">{{ stats.total_guests }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                            <Users class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border bg-card p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Total Ucapan</p>
                            <p class="text-3xl font-bold mt-2">{{ stats.total_wishes }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-orange-100 dark:bg-orange-900/20 flex items-center justify-center">
                            <MessageSquare class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border bg-card shadow-sm">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold">Undangan Terbaru</h3>
                    <p class="text-sm text-muted-foreground">5 undangan terakhir yang dibuat</p>
                </div>
                <div class="p-6">
                    <div v-if="recentWeddings.length > 0" class="space-y-4">
                        <div 
                            v-for="wedding in recentWeddings" 
                            :key="wedding.id"
                            class="flex items-center justify-between p-4 rounded-lg border hover:bg-accent transition-colors"
                        >
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-pink-500 to-purple-500 flex items-center justify-center">
                                    <Heart class="h-5 w-5 text-white" />
                                </div>
                                <div>
                                    <p class="font-semibold">{{ wedding.bride_name }} & {{ wedding.groom_name }}</p>
                                    <p class="text-sm text-muted-foreground">{{ formatDate(wedding.wedding_date) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="text-center">
                                    <p class="text-2xl font-bold">{{ wedding.guests_count }}</p>
                                    <p class="text-xs text-muted-foreground">Tamu</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold">{{ wedding.wishes_count }}</p>
                                    <p class="text-xs text-muted-foreground">Ucapan</p>
                                </div>
                                <Link 
                                    :href="`/admin/weddings/${wedding.id}`"
                                    class="px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors text-sm font-medium"
                                >
                                    Lihat Detail
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-12">
                        <Heart class="h-12 w-12 text-muted-foreground/50 mx-auto mb-4" />
                        <p class="text-muted-foreground">Belum ada undangan</p>
                        <Link 
                            href="/admin/weddings/create"
                            class="inline-block mt-4 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors text-sm font-medium"
                        >
                            Buat Undangan Pertama
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
