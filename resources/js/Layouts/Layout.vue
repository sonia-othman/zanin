<template>
  <div class="min-h-screen bg-white ">
    <NavBar />

    <main>
      <slot />
    </main>

    <footer class="bg-gray-800 text-white mt-12">
      <div class="container mx-auto px-4 py-6">
        <div class="text-center text-gray-400">
          <p>&copy; 2025 ZaninTech. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import NavBar from '@/Components/NavBar.vue'
</script>
