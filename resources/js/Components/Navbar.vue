<template>
  <header class="bg-white text-black shadow-lg">
    <div class="container mx-auto px-4 py-4">
      <div class="flex items-center justify-between">
        <!-- Left: Hamburger Menu (Mobile only) -->
        <div class="w-1/3 md:w-1/3">
          <button
            @click="toggleMobileMenu"
            class="md:hidden p-2 text-black hover:text-gray-600 transition"
            aria-label="Toggle mobile menu"
          >
            <svg
              class="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
            </svg>
          </button>
        </div>

        <!-- Center: Logo Image -->
        <div class="w-1/3 flex justify-center">
          <Link href="/">
            <img src="/images/logo.png" alt="Logo" class="h-10" />
          </Link>
        </div>

        <!-- Right: Search Input and Language Switcher -->
        <div class="w-1/3 flex justify-end items-center space-x-4">
          <!-- Search functionality -->
          <transition name="fade">
            <div
              v-if="showSearch"
              class="relative"
              style="width: 200px;"
            >
              <Search class="w-5 h-5 text-black absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search..."
                class="pl-10 pr-8 py-1 rounded border border-gray-300 text-black w-full focus:outline-none focus:ring"
                @keydown.enter="performSearch"
                ref="searchInput"
              />
              <button
                @click="toggleSearch"
                class="absolute right-1 top-1/2 -translate-y-1/2 text-black hover:text-gray-600"
                aria-label="Close search"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </transition>

          <button v-if="!showSearch" @click="toggleSearch">
            <Search class="w-8 h-6 text-black hover:text-gray-600 transition" />
          </button>

          <!-- Language Switcher Dropdown -->
          <select
            v-model="selectedLang"
            @change="changeLanguage"
            class="border border-gray-300 rounded px-2 py-1 text-black cursor-pointer"
            aria-label="Select Language"
          >
            <option value="en">English</option>
            <option value="ar">العربية</option>
            <option value="ku">کوردی</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div
      v-if="showMobileMenu"
      class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
      @click="closeMobileMenu"
    />

    <!-- Mobile Menu Sidebar -->
    <div
      :class="[
        'fixed top-0 left-0 h-full w-80 bg-white shadow-lg z-50 transform transition-transform duration-300 ease-in-out md:hidden',
        showMobileMenu ? 'translate-x-0' : '-translate-x-full'
      ]"
    >
      <!-- Mobile Menu Header -->
      <div class="flex items-center justify-between p-4 border-b">
        <h2 class="text-xl font-semibold">Menu</h2>
        <button
          @click="closeMobileMenu"
          class="p-2 text-gray-600 hover:text-gray-800"
          aria-label="Close menu"
        >
          <svg
            class="w-6 h-6"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      <!-- Mobile Menu Content -->
      <div class="p-4 overflow-y-auto h-full">
        <h3 class="text-lg font-semibold mb-4 text-gray-900">Categories</h3>
        
        <div class="space-y-4">
          <div v-for="category in categories" :key="category.id">
            <details class="group [&_summary::-webkit-details-marker]:hidden">
              <summary
                class="flex justify-between items-center cursor-pointer text-gray-800 font-medium hover:text-blue-600 select-none px-2 py-2 rounded-md transition-colors"
              >
                <span>{{ getCategoryName(category) }}</span>
                <svg
                  class="w-4 h-4 text-gray-400 group-open:rotate-90 transition-transform duration-300"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  viewBox="0 0 24 24"
                >
                  <path d="M9 5l7 7-7 7"></path>
                </svg>
              </summary>

              <div class="mt-2 ml-4 space-y-3">
                <div v-for="sub in category.sub_categories" :key="sub.id">
                  <details class="group [&_summary::-webkit-details-marker]:hidden">
                    <summary
                      class="flex justify-between items-center cursor-pointer text-gray-700 hover:text-blue-500 select-none px-2 py-1 rounded-md transition-colors"
                    >
                      <span>{{ getSubCategoryName(sub) }}</span>
                      <svg
                        class="w-3 h-3 text-gray-400 group-open:rotate-90 transition-transform duration-300"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        viewBox="0 0 24 24"
                      >
                        <path d="M9 5l7 7-7 7"></path>
                      </svg>
                    </summary>

                    <div class="mt-2 ml-4 space-y-2">
                      <Link
                        v-for="article in sub.articles"
                        :key="article.id"
                        :href="route('articles.show', article.slug)"
                        class="block text-gray-600 hover:text-blue-600 transition-colors py-1 px-2 rounded"
                        @click="closeMobileMenu"
                      >
                        {{ getArticleTitle(article) }}
                      </Link>
                    </div>
                  </details>
                </div>
              </div>
            </details>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, watch, nextTick, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { Search } from 'lucide-vue-next'
import { route } from 'ziggy-js'

const page = usePage()
const categories = computed(() => page.props.categories || [])
const currentLocale = computed(() => page.props.locale || 'en')

const showSearch = ref(false)
const searchQuery = ref('')
const searchInput = ref(null)
const showMobileMenu = ref(false)

const selectedLang = ref(currentLocale.value)

// Watch for locale changes and update selectedLang
watch(currentLocale, (newLocale) => {
  selectedLang.value = newLocale
})

// Helper functions to get translated names
function getCategoryName(category) {
  return category.translation?.name || category.name || 'Untitled Category'
}

function getSubCategoryName(subCategory) {
  return subCategory.translation?.name || subCategory.name || 'Untitled Subcategory'
}

function getArticleTitle(article) {
  return article.translation?.title || article.title || 'Untitled Article'
}

// Replace the changeLanguage function in your Navbar.vue script section:

function changeLanguage() {
  if (selectedLang.value !== currentLocale.value) {
    // Use Inertia's router.visit with full page reload
    router.visit(`/lang/${selectedLang.value}`, {
      method: 'get',
      preserveScroll: false,
      preserveState: false,
      replace: false,
      onSuccess: () => {
        console.log('Language changed successfully to:', selectedLang.value)
      },
      onError: (errors) => {
        console.error('Language change failed:', errors)
      }
    })
  }
}

function toggleSearch() {
  showSearch.value = !showSearch.value
  if (showSearch.value) {
    nextTick(() => {
      searchInput.value?.focus()
    })
  }
}

function performSearch() {
  if (searchQuery.value.trim() !== '') {
    router.visit(`/search?q=${encodeURIComponent(searchQuery.value)}`)
  }
}

function toggleMobileMenu() {
  showMobileMenu.value = !showMobileMenu.value
}

function closeMobileMenu() {
  showMobileMenu.value = false
}

// Close mobile menu on escape key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && showMobileMenu.value) {
    closeMobileMenu()
  }
})
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>