<template>
  <!-- Hide sidebar on mobile, show on desktop -->
<aside class="hidden md:block w-64 bg-white shadow-md p-6 overflow-auto h-screen sticky top-0">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 border-b border-gray-200 pb-3">
      {{$t('categories.title')}}
    </h2>

    <ul class="space-y-6">
      <li v-for="category in categories" :key="category.id">
        <details class="group [&_summary::-webkit-details-marker]:hidden">
          <summary class="flex items-center justify-between cursor-pointer text-gray-800 font-normal hover:text-blue-600 select-none px-1 py-2 rounded-md transition-colors">
            <!-- Chevron icon - positioned based on text direction -->
            <ChevronRightIcon 
              class="w-5 h-5 text-gray-400 group-open:rotate-90 transition-transform duration-300 flex-shrink-0"
              :class="isRTL ? 'order-first mr-2' : 'order-last ml-2'"
            />
            
            <!-- Category name with proper text direction -->
            <span class="flex-1" :class="isRTL ? 'text-right' : 'text-left'">
              {{ category.translation?.name || category.name || 'Unnamed Category' }}
            </span>
          </summary>

          <ul class="mt-3 border-l border-gray-300 space-y-4" :class="isRTL ? 'mr-5 border-r border-l-0 pr-4' : 'ml-5 pl-4'">
            <li v-for="sub in category.sub_categories" :key="sub.id">
              <details class="group [&_summary::-webkit-details-marker]:hidden">
                <summary class="flex items-center justify-between cursor-pointer text-gray-800 font-normal hover:text-blue-600 select-none px-1 py-2 rounded-md transition-colors">
                  <!-- Chevron icon - positioned based on text direction -->
                  <ChevronRightIcon 
                    class="w-5 h-5 text-gray-400 group-open:rotate-90 transition-transform duration-300 flex-shrink-0"
                    :class="isRTL ? 'order-first mr-2' : 'order-last ml-2'"
                  />
                  
                  <!-- Subcategory name with proper text direction -->
                  <span class="flex-1" :class="isRTL ? 'text-right' : 'text-left'">
                    {{ sub.translation?.name || sub.name || 'Unnamed Subcategory' }}
                  </span>
                </summary>

                <ul class="mt-2 border-l border-gray-200 space-y-2" :class="isRTL ? 'mr-4 border-r border-l-0 pr-3' : 'ml-4 pl-3'">
                  <li v-for="article in sub.articles" :key="article.id">
                    <Link
                      :href="route('articles.show', article.slug)"
                      class="block text-gray-600 hover:text-blue-600 transition-colors"
                      :class="isRTL ? 'text-right' : 'text-left'"
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
import { ChevronRightIcon } from '@heroicons/vue/24/solid'
import { computed } from 'vue'

const props = defineProps({
  categories: Array,
  locale: String, // Add locale prop
})

// Determine if current locale is RTL
const isRTL = computed(() => {
  return props.locale === 'ar' || props.locale === 'ku'
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