<template>
  <!-- Hide sidebar on mobile, show on desktop -->
<aside class="hidden md:block w-64 bg-white shadow-lg rounded-md p-6 overflow-auto sticky top-5 h-[calc(100vh-1.25rem)]">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 border-b border-gray-200 pb-3">Categories</h2>

    <ul class="space-y-6">
      <li v-for="category in categories" :key="category.id">
        <details class="group [&_summary::-webkit-details-marker]:hidden">
          <summary
            class="flex justify-between items-center cursor-pointer text-gray-800 font-semibold hover:text-blue-600 select-none px-1 py-2 rounded-md transition-colors"
          >
            <span>{{ category.translation?.name || category.name || 'Unnamed Category' }}</span>
            <svg
              class="w-5 h-5 text-gray-400 group-open:rotate-90 transition-transform duration-300"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path d="M9 5l7 7-7 7"></path>
            </svg>
          </summary>

          <ul class="mt-3 ml-5 border-l border-gray-300 pl-4 space-y-4">
            <li v-for="sub in category.subCategories" :key="sub.id">
              <details class="group [&_summary::-webkit-details-marker]:hidden">
                <summary
                  class="flex justify-between items-center cursor-pointer text-gray-700 font-medium hover:text-blue-500 select-none px-1 py-1 rounded-md transition-colors"
                >
                  <span>{{ sub.translation?.name || sub.name || 'Unnamed Subcategory' }}</span>
                  <svg
                    class="w-4 h-4 text-gray-400 group-open:rotate-90 transition-transform duration-300"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path d="M9 5l7 7-7 7"></path>
                  </svg>
                </summary>

                <ul class="mt-2 ml-4 border-l border-gray-200 pl-3 space-y-2">
                  <li v-for="article in sub.articles" :key="article.id">
                    <Link
                      :href="route('articles.show', article.slug)"
                      class="block text-gray-600 hover:text-blue-600 transition-colors"
                    >
                      {{ article.translation?.title || article.title || 'Untitled Article' }}
                    </Link>
                  </li>
                </ul>
              </details>
            </li>
          </ul>
        </details>
      </li>
    </ul>
  </aside>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

defineProps({
  categories: Array,
})
</script>

<style scoped>
aside {
  scrollbar-width: thin;
  scrollbar-color: #a0aec0 #f7fafc;
}
aside::-webkit-scrollbar {
  width: 7px;
}
aside::-webkit-scrollbar-thumb {
  background-color: #a0aec0;
  border-radius: 4px;
  border: 2px solid #f7fafc;
}
</style>