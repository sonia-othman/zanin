<template>
  <Link
    :href="$route('articles.show', { slug: article.slug })"
    class="group bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 hover:scale-[1.02] flex flex-col h-full min-h-[300px]"
  >
   <!-- Article Image -->
<div class="h-48 overflow-hidden">
  <img
    v-if="article.image"
    :src="`/storage/${article.image}`"
    alt="Article Image"
    class="w-full h-full object-contain bg-white transition-transform duration-300 group-hover:scale-105"
  >
  <div v-else class="h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
    <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
    </svg>
  </div>
</div>


    <!-- Content -->
    <div class="p-6 flex flex-col flex-grow">
      <h3
        class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-3 line-clamp-2"
        :class="isRTL ? 'text-right' : 'text-left'"
      >
        {{ article.translation?.title || article.title }}
      </h3>

      <p
        v-if="article.translation?.excerpt || article.excerpt"
        class="text-gray-600 text-sm mb-2 flex-grow line-clamp-3"
        :class="isRTL ? 'text-right' : 'text-left'"
      >
        {{ article.translation?.excerpt || article.excerpt }}
      </p>

      <!-- Footer with author, views, and date -->
      <div
        class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100"
        :class="isRTL ? 'flex-row-reverse' : ''"
      >
        <!-- Author on left -->
        <span
          v-if="article.author?.name"
          class="whitespace-nowrap bg-blue-100 text-blue-800 px-2 py-0.5 rounded-md text-xs font-medium flex items-center gap-1"
          :class="isRTL ? 'text-right' : 'text-left'"
        >
          {{ article.author.name }}
        </span>

        <!-- Views in center -->
        <span
          v-if="showViews && article.views !== undefined"
          class="flex items-center whitespace-nowrap"
          :class="isRTL ? 'flex-row-reverse' : ''"
        >
          <svg
            class="w-4 h-4 mr-1"
            :class="isRTL ? 'ml-1 mr-0' : 'mr-1'"
            fill="currentColor"
            viewBox="0 0 20 20"
            aria-hidden="true"
            focusable="false"
          >
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
            <path
              fill-rule="evenodd"
              d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
              clip-rule="evenodd"
            />
          </svg>
          {{ article.views }} {{ $t('common.views') }}
        </span>

        <!-- Date on right -->
        <time v-if="article.created_at" :datetime="article.created_at" class="whitespace-nowrap">
          {{ formatDate(article.created_at, locale, isRTL) }}
        </time>
      </div>
    </div>
  </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { formatDate } from '@/composable/useDateFormat'

const props = defineProps({
  article: Object,
  showViews: { type: Boolean, default: false },
  locale: String,
  isRTL: Boolean,
})
</script>
