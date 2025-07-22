<script setup>
import { usePage } from '@inertiajs/vue3'
import NavBar from '@/Components/NavBar.vue'
import Sidebar from '@/Components/Sidebar.vue'
import { computed } from 'vue'

const page = usePage()

// Use shared categories from HandleInertiaRequests middleware
const categories = computed(() => page.props.categories || [])

// Font class by language
const fontClass = computed(() => {
  const lang = page.props.locale
  return (lang === 'ku' || lang === 'ar') ? 'font-rabar' : 'font-sans'
})

// Direction class for entire layout
const directionClass = computed(() => {
  const lang = page.props.locale
  return ['ku', 'ar'].includes(lang) ? 'rtl' : 'ltr'
})

// Text direction class
const textDirectionClass = computed(() => {
  const lang = page.props.locale
  return ['ku', 'ar'].includes(lang) ? 'text-direction-rtl' : 'text-direction-ltr'
})

// Check if RTL
const isRTL = computed(() => {
  const lang = page.props.locale
  return ['ku', 'ar'].includes(lang)
})
</script>

<template>
  <div :class="[fontClass, textDirectionClass, 'min-h-screen bg-white flex flex-col']" :dir="directionClass">
    <NavBar />

    <!-- Main content area with full height -->
    <div class="flex flex-1 min-h-[calc(100vh-64px)]" :class="{ 'flex-row-reverse': isRTL }">
      <!-- Sidebar - Always first in DOM, but visually positioned by flex-row-reverse -->
      <div class="flex-shrink-0">
        <Sidebar :categories="categories" />
      </div>

      <!-- Main content -->
      <main class="flex-1 overflow-auto">
        <slot />
      </main>
    </div>

    <footer class="bg-gray-800 text-white">
      <div class="container mx-auto px-4 py-6">
        <div class="text-center text-gray-400">
          <p>&copy; 2025 ZaninTech. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>