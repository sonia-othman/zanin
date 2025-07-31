<template>
  <section class="container mx-auto px-4 py-12 bg-gray-50">
    <h2 class="text-3xl font-bold mb-8 text-gray-900 text-center">{{ $t('index.articles') }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
      <ArticleCard
        v-for="article in articles.data"
        :key="article.id"
        :article="article"
        :locale="locale"
        :is-rtl="isRTL"
      />
    </div>

    <!-- Pagination -->
    <div v-if="articles.links && articles.links.length > 3" class="mt-12 flex justify-center">
      <nav class="flex items-center space-x-2" :class="isRTL ? 'space-x-reverse' : ''">
        <Link
          v-for="link in articles.links"
          :key="link.label"
          :href="link.url"
          :class="[
            'px-4 py-2 text-sm rounded-lg transition-colors',
            link.active
              ? 'bg-blue-600 text-white'
              : link.url
              ? 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50'
              : 'bg-gray-100 text-gray-400 cursor-not-allowed',
          ]"
          v-html="link.label"
        />
      </nav>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import ArticleCard from '@/Components/ArticleCard.vue'

const props = defineProps({
  articles: Object,
})

const page = usePage()
const locale = computed(() => page.props.locale)
const isRTL = computed(() => locale.value === 'ar' || locale.value === 'ku')
</script>
