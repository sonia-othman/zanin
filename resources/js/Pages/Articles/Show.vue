<template>
  <Layout>
    <div class="container mx-auto px-4 py-8 flex justify-start">
      <article class="w-full md:w-2/3 bg-white rounded-lg p-8"> <!-- Removed border and shadow -->
        <header class="mb-8">
          <h1 class="text-4xl font-bold text-gray-900 mb-4">
            {{ article.title }}
          </h1>

          <div class="flex items-center text-sm text-gray-500 mb-6">
            <span v-if="article.category" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-3">
              {{ article.category.name }}
            </span>
            <time v-if="article.created_at" :datetime="article.created_at">
              {{ formatDate(article.created_at) }}
            </time>
          </div>
        </header>

        <!-- Show featured image if exists -->
        <div v-if="article.image" class="mb-8">
          <img 
            :src="'/storage/' + article.image" 
            :alt="article.title"
            class="w-full h-64 object-cover rounded-lg" <!-- removed border and shadow -->
          />
        </div>

        <!-- Rich editor content with inline images -->
        <div class="prose prose-lg prose-slate max-w-none article-content" v-html="article.content"></div>
      </article>
    </div>

    <div class="container mx-auto px-4 mt-8">
      <Link href="/" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
        ← Back to Articles
      </Link>
    </div>
  </Layout>
</template>


<script setup>
import { Link } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';

defineProps({
  article: Object,
});

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};
</script>

<style scoped>
/* Enhanced styles for article content with inline images */
.article-content {
  @apply text-gray-800 leading-relaxed;
}

.article-content :deep(img) {
  @apply rounded-lg  max-w-full h-auto my-6;
  @apply mx-auto block; /* Center images */

  max-width: 800px;   /* max width of inline images */
  max-height: 600px;  /* max height to keep images not too tall */
  width: 100%;        /* scale down images on smaller screens */
  height: auto;       /* keep aspect ratio */
}


.article-content :deep(h2) {
  @apply text-2xl font-bold mt-8 mb-4 text-gray-900;
}

.article-content :deep(h3) {
  @apply text-xl font-semibold mt-6 mb-3 text-gray-900;
}

.article-content :deep(h4) {
  @apply text-lg font-semibold mt-4 mb-2 text-gray-900;
}

.article-content :deep(p) {
  @apply mb-4 text-gray-700;
}

.article-content :deep(ul) {
  @apply list-disc list-inside mb-4 space-y-2;
}

.article-content :deep(ol) {
  @apply list-decimal list-inside mb-4 space-y-2;
}

.article-content :deep(li) {
  @apply text-gray-700 ml-4;
}

.article-content :deep(blockquote) {
  @apply border-l-4 border-blue-500 pl-4 italic text-gray-700 bg-gray-50 py-2 my-4;
}

.article-content :deep(code) {
  @apply bg-gray-100 px-2 py-1 rounded text-sm font-mono text-gray-800;
}

.article-content :deep(pre) {
  @apply bg-gray-800 text-white p-4 rounded-lg overflow-x-auto my-4;
}

.article-content :deep(pre code) {
  @apply bg-transparent px-0 py-0 text-white;
}

.article-content :deep(strong) {
  @apply font-bold text-gray-900;
}

.article-content :deep(em) {
  @apply italic;
}

.article-content :deep(a) {
  @apply text-blue-600 hover:text-blue-800 underline;
}
</style>