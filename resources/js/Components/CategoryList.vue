<template>
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
          role="button"
          tabindex="0"
          @keydown.enter="goToCategory(category.slug)"
        >
          <span class="text-2xl font-medium">{{ category.translation?.name || category.name }}</span>
        </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  categories: Array,
})

const categories = ref(props.categories || [])

function goToCategory(slug) {
  router.get(route('categories.show', { slug }))
}

const gradientList = [
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
  if (!categoryId) return gradientList[0]
  const index = categoryId % gradientList.length
  return gradientList[index]
}
</script>