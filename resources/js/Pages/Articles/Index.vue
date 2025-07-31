<template>
  <Layout>
    <SearchHero :filters="filters" />
    <CategoryList :categories="categories" />
    <PopularArticles :popularArticles="popularArticles" />
    <LatestArticles :articles="articles" />
  </Layout>
</template>

<script setup>
import Layout from '@/Layouts/Layout.vue'
import SearchHero from '@/Components/SearchHero.vue'
import CategoryList from '@/Components/CategoryList.vue'
import PopularArticles from '@/Components/PopularArticles.vue'
import LatestArticles from '@/Components/LatestArticles.vue'

const props = defineProps({
  articles: Object,
  filters: Object,
  categories: Array,
  popularArticles: Array,
})
</script>