<template>
  <Layout>
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-6">
        Search results for "{{ search }}"
      </h1>

      <div v-if="articles.data.length === 0" class="text-gray-600">
        No articles found.
      </div>

      <div class="space-y-6" v-else>
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
            <span
              v-if="article.category"
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-3"
            >
              {{ article.category.name }}
            </span>
            <time v-if="article.created_at" :datetime="article.created_at">
              {{ new Date(article.created_at).toLocaleDateString() }}
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
    </div>
  </Layout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'

defineProps({
  articles: Object,
  search: String,
})
</script>
