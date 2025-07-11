<template>
  <Layout>
    <section class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-6 text-gray-900">
        Articles in category: "{{ category.name }}"
      </h1>

      <div class="space-y-6">
        <article
          v-for="article in articles.data"
          :key="article.id"
          class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow"
        >
          <Link
            :href="$route('articles.show', { slug: article.slug })"
            class="block group"
          >
            <h2 class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-2">
              {{ article.title }}
            </h2>
          </Link>

          <p v-if="article.excerpt" class="text-gray-600 mb-4">
            {{ article.excerpt }}
          </p>

          <div class="flex items-center text-sm text-gray-500">
            <time v-if="article.created_at" :datetime="article.created_at">
              {{ formatDate(article.created_at) }}
            </time>
          </div>
        </article>
      </div>

      <!-- Pagination -->
      <div
        class="mt-8 flex justify-between items-center"
        v-if="articles.prev_page_url || articles.next_page_url"
      >
        <Link
          v-if="articles.prev_page_url"
          :href="articles.prev_page_url"
          class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
        >
          ← Previous
        </Link>
        <div v-else></div>

        <span class="text-sm text-gray-700">
          Page {{ articles.current_page }} of {{ articles.last_page }}
        </span>

        <Link
          v-if="articles.next_page_url"
          :href="articles.next_page_url"
          class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
        >
          Next →
        </Link>
        <div v-else></div>
      </div>
    </section>
  </Layout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'

const props = defineProps({
  category: Object,
  articles: Object,
})

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}
</script>
