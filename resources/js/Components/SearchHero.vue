<template>
  <!-- Search Hero Section -->
  <section class="bg-gradient-to-b from-blue-400 via-blue-200 to-white text-white py-20">
    <div class="container mx-auto text-center px-4">
      <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ $t('index.searchTitle') }}</h1>
      <p class="text-lg md:text-xl mb-8">{{ $t('index.searchDescription') }}</p>

      <div class="relative max-w-4xl mx-auto">
        <!-- Search Input -->
        <div class="flex items-center bg-white rounded-full shadow-sm overflow-hidden">
          <input
            type="text"
            v-model="search"
            :placeholder="$t('search.placeholder')"
            class="w-full px-6 py-3 text-gray-700 focus:outline-none"
            :class="isRTL ? 'text-right' : 'text-left'"
            @keyup.enter="submitSearch"
            @blur="hideSuggestions"
            @focus="showSuggestionsIfAvailable"
            aria-label="Search articles"
          />
          <button
            type="button"
            aria-label="Search articles"
            @click="submitSearch"
            class="bg-blue-400 text-white px-6 py-3 font-semibold hover:bg-blue-600"
          >
             {{ $t('search.name') }}
          </button>
        </div>

        <!-- Loading -->
        <div
          v-if="loading"
          class="absolute left-0 right-0 z-50 bg-white rounded-lg shadow-lg mt-2 text-center py-2"
        >
          <span class="text-gray-500">{{ $t('common.loading') }}</span>
        </div>

        <!-- Suggestions -->
        <ul
          v-if="showSuggestions && Object.keys(suggestions).length > 0 && !loading"
          class="absolute left-0 right-0 z-50 bg-white rounded-lg shadow-lg mt-2 overflow-hidden border border-gray-300"
          :class="isRTL ? 'text-right' : 'text-left'"
          aria-live="polite"
        >
          <li
            v-for="(title, slug) in suggestions"
            :key="slug"
            @mousedown.prevent="goToArticle(slug)"
            class="px-6 py-3 cursor-pointer hover:bg-gray-100 text-gray-700"
          >
            {{ title }}
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import axios from 'axios'
import { router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  filters: Object,
})

const page = usePage()
const locale = computed(() => page.props.locale)
const isRTL = computed(() => locale.value === 'ar' || locale.value === 'ku')

const search = ref(props.filters?.search || '')
const suggestions = ref({})
const showSuggestions = ref(false)
const loading = ref(false)

let debounceTimeout = null
let currentRequest = null

// Cache for autocomplete
const suggestionCache = new Map()

watch(search, (newValue) => {
  if (debounceTimeout) clearTimeout(debounceTimeout)
  if (currentRequest) {
    currentRequest.cancel()
    currentRequest = null
  }

  const trimmedValue = newValue.trim()

  if (trimmedValue.length > 1) {
    if (suggestionCache.has(trimmedValue)) {
      suggestions.value = suggestionCache.get(trimmedValue)
      showSuggestions.value = true
      loading.value = false
      return
    }

    loading.value = true
    debounceTimeout = setTimeout(async () => {
      try {
        const source = axios.CancelToken.source()
        currentRequest = source

        const res = await axios.get('/articles/autocomplete?q=' + encodeURIComponent(trimmedValue), {
          cancelToken: source.token,
        })

        suggestionCache.set(trimmedValue, res.data)
        suggestions.value = res.data
        showSuggestions.value = true
        loading.value = false
        currentRequest = null
      } catch (error) {
        if (!axios.isCancel(error)) {
          console.error('Search error:', error)
          suggestions.value = {}
          showSuggestions.value = false
        }
        loading.value = false
        currentRequest = null
      }
    }, 300)
  } else {
    suggestions.value = {}
    showSuggestions.value = false
    loading.value = false
  }
})

function submitSearch() {
  if (search.value.trim()) {
    showSuggestions.value = false
    loading.value = false
    router.get('/search', { q: search.value.trim() })
  }
}

function goToArticle(slug) {
  showSuggestions.value = false
  loading.value = false
  router.get(route('articles.show', { slug }))
}

function hideSuggestions() {
  setTimeout(() => {
    showSuggestions.value = false
  }, 200)
}

function showSuggestionsIfAvailable() {
  if (Object.keys(suggestions.value).length > 0) {
    showSuggestions.value = true
  }
}
</script>