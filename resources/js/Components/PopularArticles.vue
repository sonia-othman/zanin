<template>
  <section class="container mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-8 text-gray-900 text-center">{{ $t('index.mostViewed') }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
      <ArticleCard
        v-for="article in popularArticles"
        :key="article.id"
        :article="article"
        :locale="locale"
        :is-rtl="isRTL"
        :show-views="true"
      />
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import ArticleCard from '@/Components/ArticleCard.vue'

const props = defineProps({
  popularArticles: Array,
})

const page = usePage()
const locale = computed(() => page.props.locale)
const isRTL = computed(() => locale.value === 'ar' || locale.value === 'ku')
</script>
