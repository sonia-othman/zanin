<template>
  <Layout>
    <section class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold mb-6 text-gray-900">
        {{ $t('categories.title') }}: "{{ category.translation?.name || category.name }}"
      </h1>

      <div class="space-y-6">
        <article
          v-for="article in articles.data"
          :key="article.id"
          class="flex flex-col md:flex-row bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
        >
          <!-- Image placeholder (replace with actual image if you have one) -->
          <div class="w-full md:w-1/3 h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
            </svg>
          </div>

          <!-- Content -->
          <div class="p-6 flex flex-col justify-between w-full md:w-2/3">
            <div>
              <Link
                :href="$route('articles.show', { slug: article.slug })"
                class="group"
              >
                <h2
                  class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-2 line-clamp-2"
                >
                  {{ article.translation?.title || article.title }}
                </h2>
              </Link>

              <p
                v-if="article.translation?.excerpt || article.excerpt"
                class="text-gray-600 text-sm line-clamp-3"
              >
                {{ article.translation?.excerpt || article.excerpt }}
              </p>
            </div>

            <!-- Footer -->
            <div class="flex justify-between items-center text-xs text-gray-500 mt-4">
              <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded-full text-xs">
                {{ category.translation?.name || category.name }}
              </span>
              <time v-if="article.created_at" :datetime="article.created_at">
                {{ formatDate(article.created_at, locale.value, isRTL.value) }}
              </time>
            </div>
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
          ← {{ $t('common.previous') }}
        </Link>
        <div v-else></div>

        <span class="text-sm text-gray-700">
          {{ $t('common.page') }} {{ articles.current_page }} {{ $t('common.of') }} {{ articles.last_page }}
        </span>

        <Link
          v-if="articles.next_page_url"
          :href="articles.next_page_url"
          class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
        >
          {{ $t('common.next') }} →
        </Link>
        <div v-else></div>
      </div>
    </section>
  </Layout>
</template>


<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'
import { formatDate } from '@/composable/useDateFormat'
import { computed } from 'vue'

const props = defineProps({
  category: Object,
  articles: Object,
})

const page = usePage()
const locale = computed(() => page.props.locale)
const isRTL = computed(() => locale.value === 'ar' || locale.value === 'ku')
</script>

