<template>
  <Layout>
    <div class="container mx-auto px-4 py-8">
      <!-- Remove the flex justify classes that were causing issues -->
      <div class="flex" :class="isRTL ? 'justify-start' : 'justify-start'">
        <article class="w-full md:max-w-4xl bg-white rounded-lg p-8" 
                 :class="isRTL ? 'md:ml-auto md:mr-0' : 'md:mr-auto md:ml-0'">
          <header class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-4" 
                :class="isRTL ? 'text-right' : 'text-left'">
              {{ article.translation?.title || article.title }}
            </h1>

            <div class="flex items-center text-sm text-gray-500 mb-6"
                 :class="isRTL ? 'justify-start flex-row-reverse' : 'justify-start'">
              <time v-if="article.created_at" 
                    :datetime="article.created_at"
                    class="inline-block">
                {{ formatDate(article.created_at) }}
              </time>
              <span v-if="article.category" 
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                    :class="isRTL ? 'mr-3' : 'ml-3'">
                {{ article.category.name }}
              </span>
            </div>
          </header>

          <!-- Show featured image if exists -->
          <div v-if="article.image" class="mb-8">
            <img 
              :src="'/storage/' + article.image" 
              :alt="article.title"
              class="w-full h-64 object-cover rounded-lg" 
            />
          </div>

          <!-- Rich editor content with inline images -->
          <div class="prose prose-lg prose-slate max-w-none article-content" 
               :class="isRTL ? 'text-right' : 'text-left'"
               v-html="article.translation?.content || article.content">
          </div>
        </article>
      </div>
    </div>

    <div class="container mx-auto px-4 mt-8">
      <Link href="/" 
            class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors"
            :class="isRTL ? 'flex-row-reverse' : ''">
          <span :class="isRTL ? 'mr-2' : 'ml-2'">{{ $t('common.back_to_articles') }}</span>
      </Link>
    </div>
  </Layout>
</template>

<script setup>
import { usePage, Link, router } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';
import { computed , onMounted } from 'vue';


const page = usePage();

const article = page.props.article;

const locale = computed(() => page.props.locale);

const isRTL = computed(() => locale.value === 'ar' || locale.value === 'ku');

onMounted(() => {
  const articleContent = document.querySelector('.article-content');
  if (!articleContent) return;

  articleContent.querySelectorAll('a').forEach(link => {
    const href = link.getAttribute('href');

    // Check if href is internal link (starts with / and no protocol)
    if (href && href.startsWith('/') && !href.startsWith('//')) {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        router.visit(href);
      });
    }
  });
});

const formatDate = (dateString) => {
const date = new Date(dateString);
  
  // Use appropriate locale for date formatting
  if (isRTL.value) {
    if (locale.value === 'ar') {
      return date.toLocaleDateString('ar-SA', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      });
    } else if (locale.value === 'ku') {
      // Kurdish formatting - you may want to adjust this based on your preferences
      return date.toLocaleDateString('ku-IQ', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      });
    }
  }
  
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};
</script>

<style scoped>
.article-content {
  @apply text-gray-800 leading-relaxed;
}

.article-content :deep(img) {
  @apply rounded-lg max-w-full h-auto my-6;
  @apply mx-auto block; 
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
  @apply list-disc mb-4 space-y-2;
  padding-inline-start: 2rem;
}

.article-content :deep(ol) {
  @apply list-decimal mb-4 space-y-2;
  padding-inline-start: 2rem;
}

.article-content :deep(li) {
  @apply text-gray-700;
}

.article-content :deep(blockquote) {
  @apply border-blue-500 pl-4 italic text-gray-700 bg-gray-50 py-2 my-4;
  border-inline-start-width: 4px;
  padding-inline-start: 1rem;
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