<script setup>
import { usePage } from '@inertiajs/vue3'
import NavBar from '@/Components/NavBar.vue'
import Sidebar from '@/Components/Sidebar.vue'
import { computed, watch } from 'vue'

const page = usePage()

const categories = computed(() => page.props.categories || [])

// Make this reactive to page.props.locale changes
const fontClass = computed(() => {
  const lang = page.props.locale
  console.log('Current locale for font:', lang) // Debug log
  return (lang === 'ku' || lang === 'ar') ? 'font-rabar' : 'font-sans'
})

// Watch for locale changes and log them
watch(() => page.props.locale, (newLocale) => {
  console.log('Locale changed to:', newLocale)
}, { immediate: true })
</script>

<template>
  <div :class="[fontClass, 'min-h-screen bg-white flex flex-col']">
    <NavBar />

    <div class="flex flex-1">
      <Sidebar :categories="categories" />

      <main class="flex-1">
        <slot />
      </main>
    </div>

    <footer class="bg-gray-800 text-white mt-12">
      <div class="container mx-auto px-4 py-6">
        <div class="text-center text-gray-400">
          <p>&copy; 2025 ZaninTech. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>