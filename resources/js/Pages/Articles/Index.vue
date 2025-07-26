<template>
  <Layout>
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
              @keyup.enter="submitSearch"
              @blur="hideSuggestions"
              @focus="showSuggestionsIfAvailable"
            />
            <button
              @click="submitSearch"
              class="bg-blue-400 text-white px-6 py-3 font-semibold hover:bg-blue-600"
            >
               {{ $t('search.name') }}
            </button>
          </div>

          <!-- Loading -->
          <div v-if="loading" class="absolute left-0 right-0 z-50 bg-white rounded-lg shadow-lg mt-2 text-center py-2">
            <span class="text-gray-500">{{ $t('common.loading') }}</span>
          </div>

          <!-- Suggestions -->
          <ul
            v-if="showSuggestions && Object.keys(suggestions).length > 0 && !loading"
            class="absolute left-0 right-0 z-50 bg-white rounded-lg shadow-lg mt-2 text-left overflow-hidden border border-gray-300"
          >
            <li
              v-for="(title, slug) in suggestions"
              :key="slug"
              @mousedown="goToArticle(slug)"
              class="px-6 py-3 cursor-pointer hover:bg-gray-100 text-gray-700"
            >
              {{ title }}
            </li>
          </ul>
        </div>
      </div>
    </section>

    <!-- Categories Section -->
    <section class="container mx-auto px-4 py-12">
      <h2 class="text-3xl font-bold mb-8 text-gray-900 text-center">{{ $t('categories.title') }}</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 justify-center max-w-4xl mx-auto">
          <div
            v-for="category in categories"
            :key="category.id"
            @click="goToCategory(category.slug)"
            class="min-h-[160px] flex items-center justify-center cursor-pointer rounded-xl shadow-sm text-white p-6 text-center hover:scale-105 transform transition duration-300"
            :class="'bg-gradient-to-b ' + getGradient(category.id)"
          >
<span class="text-2xl font-medium">{{ category.translation?.name || category.name }}</span>
          </div>
      </div>
    </section>

        <!-- Most Viewed Articles -->
        <section class="container mx-auto px-4 py-12">
          <h2 class="text-3xl font-bold mb-8 text-gray-900 text-center">{{ $t('index.mostViewed') }}</h2>
        <div class="grid grid-cols-1 gap-8 max-w-4xl mx-auto">
            <Link
              v-for="article in popularArticles"
              :key="article.id"
              :href="$route('articles.show', { slug: article.slug })"
              class="block bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow w-full group"
            >
              <h3 class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-2">
{{ article.translation?.title || article.title }}
              </h3>

              

              <div class="flex items-center text-sm text-gray-500 justify-between">
                <span>{{ article.views }} {{ $t('common.views') }}</span>
                <time v-if="article.created_at" :datetime="article.created_at">
                  {{ formatDate(article.created_at) }}
                </time>
              </div>
            </Link>

          </div>
        </section>


    <!-- Articles List -->
    <section class="container mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-8 text-gray-900 text-center">{{ $t('index.articles') }}</h2>

    <div class="grid grid-cols-1 gap-8 max-w-4xl mx-auto">
        <Link
          v-for="article in articles.data"
          :key="article.id"
          :href="$route('articles.show', { slug: article.slug })"
          class="block bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow w-full group"
        >
          <h3 class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition-colors mb-2">
  {{ article.translation?.title || article.title }}
</h3>

      
          <div class="flex justify-end text-sm text-gray-500 ">
            <time v-if="article.created_at" :datetime="article.created_at">
              {{ formatDate(article.created_at) }}
            </time>
          </div>
        </Link>

  </div>
</section>

  </Layout>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import { Link, router } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'

const props = defineProps({
  articles: Object,
  filters: Object,
  categories: Array,
  popularArticles: Array,
})

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

const categories = ref(props.categories || [])

function goToCategory(slug) {
  router.get(route('categories.show', { slug }))
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}
const gradientList = [
  // Your current colors
  'from-emerald-600 via-emerald-600/80 to-emerald-300',
  'from-fuchsia-600 via-fuchsia-600/80 to-fuchsia-300',
  'from-rose-600 via-rose-600/80 to-rose-300',
  'from-cyan-600 via-cyan-600/80 to-cyan-300',
  'from-teal-600 via-teal-600/80 to-teal-300',
  'from-blue-600 via-blue-600/80 to-blue-300',
  'from-red-600 via-red-600/80 to-red-300',
  'from-yellow-600 via-yellow-600/80 to-yellow-300',
  'from-green-600 via-green-600/80 to-green-300',
  'from-pink-600 via-pink-600/80 to-pink-300',
  'from-purple-600 via-purple-600/80 to-purple-300',
  'from-indigo-600 via-indigo-600/80 to-indigo-300',
  
  'from-orange-600 via-orange-600/80 to-orange-300',
  'from-amber-600 via-amber-600/80 to-amber-300',
  'from-lime-600 via-lime-600/80 to-lime-300',
  'from-violet-600 via-violet-600/80 to-violet-300',
  'from-sky-600 via-sky-600/80 to-sky-300',
  'from-blue-600 via-purple-600/80 to-pink-300',
  'from-emerald-600 via-teal-600/80 to-cyan-300',
  'from-orange-600 via-red-600/80 to-pink-300',
  'from-yellow-600 via-orange-600/80 to-red-300',
  'from-lime-600 via-green-600/80 to-emerald-300',
  'from-indigo-600 via-blue-600/80 to-sky-300',
]


function getGradient(categoryId) {
  const index = categoryId % gradientList.length
  return gradientList[index]
}

</script>
