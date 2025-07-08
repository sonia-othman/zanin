<template>
  <header class="bg-white text-black shadow-lg">
    <div class="container mx-auto px-4 py-4">
      <div class="flex items-center justify-between">
        <!-- Left (empty for spacing) -->
        <div class="w-1/3"></div>

        <!-- Center: Logo Image -->
        <div class="w-1/3 flex justify-center">
          <Link href="/">
            <img src="/images/logo.png" alt="Logo" class="h-10" />
          </Link>
        </div>

        <!-- Right: Search Input with icons -->
        <div class="w-1/3 flex justify-end items-center">
          <transition name="fade">
            <div
              v-if="showSearch"
              class="relative"
              style="width: 200px;"
            >
              <!-- Search icon inside input (left) -->
              <Search class="w-5 h-5 text-black absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" />

              <!-- Input -->
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search..."
                class="pl-10 pr-8 py-1 rounded border border-gray-300 text-black w-full focus:outline-none focus:ring"
                @keydown.enter="performSearch"
                ref="searchInput"
              />

              <!-- X icon to close (right) -->
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

          <!-- Show search icon button when input is closed -->
          <button v-if="!showSearch" @click="toggleSearch">
            <Search class="w-8 h-6 text-black hover:text-gray-600 transition" />
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Search } from 'lucide-vue-next'

const showSearch = ref(false)
const searchQuery = ref('')
const searchInput = ref(null)

function toggleSearch() {
  showSearch.value = !showSearch.value
  if (showSearch.value) {
    // Focus input when it shows
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
</script>

<style>
/* Fade transition for input */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
