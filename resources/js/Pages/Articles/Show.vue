<template>
  <Layout>
    <div class="container mx-auto px-4 py-8">
      <div class="flex" :class="isRTL ? 'justify-start' : 'justify-start'">
        <article class="w-full md:max-w-4xl bg-white rounded-lg p-8"
                 :class="isRTL ? 'md:ml-auto md:mr-0' : 'md:mr-auto md:ml-0'">
          <header class="mb-8">
            <h1 class="font-bold text-gray-900 mb-4 leading-tight"
                :class="isRTL ? 'text-right' : 'text-left'"
                style="font-size: clamp(2rem, 5vw, 4rem);">
              {{ article.translation?.title || article.title }}
            </h1>

            <div class="flex items-center text-gray-500 mb-6"
                 :class="isRTL ? 'justify-start flex-row-reverse' : 'justify-start'"
                 style="font-size: clamp(0.875rem, 2vw, 1rem);">
              <time v-if="article.created_at"
              :datetime="article.created_at"
              class="inline-block">
          {{ formatDate(article.created_at) }}
        </time>
          
        <div class="flex gap-2 font-medium ml-4">
           <!-- Category -->
                  <span v-if="article.sub_category?.category?.name"
                        class="bg-green-100 text-green-800 px-3 py-2 rounded-full">
                    {{ article.sub_category.category.name }}
                  </span>
                  <!-- Subcategory -->
                  <span v-if="article.sub_category?.name"
                        class="bg-blue-100 text-blue-800 px-3 py-2 rounded-full">
                    {{ article.sub_category.name }}
                  </span>
                </div>
            </div>
          </header>

          <div class="prose max-w-none article-content"
               :class="[
                 isRTL ? 'text-right font-rabar' : 'text-left font-sans'
               ]"
               v-html="article.content_html">
          </div>
        </article>
      </div>
    </div>

    <div class="container mx-auto px-4 mt-8">
      <Link href="/"
            class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors"
            :class="isRTL ? 'flex-row-reverse' : ''"
            style="font-size: clamp(1rem, 2.5vw, 1.125rem);">
        <span :class="isRTL ? 'mr-2' : 'ml-2'">{{ $t('common.back_to_articles') }}</span>
      </Link>
    </div>
  </Layout>
</template>

<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';
import { computed } from 'vue';

const page = usePage();
const article = page.props.article;
const locale = computed(() => page.props.locale);
const isRTL = computed(() => locale.value === 'ar' || locale.value === 'ku');

const formatDate = (dateString) => {
  const date = new Date(dateString);

  if (isRTL.value) {
    if (locale.value === 'ar') {
      return date.toLocaleDateString('ar-SA', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      });
    } else if (locale.value === 'ku') {
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
  /* Fluid font size that scales with screen size */
  font-size: clamp(1rem, 2.5vw, 1.375rem);
  line-height: 1.7;
}

/* Images */
.article-content :deep(img) {
  display: block;
  max-width: 100%;
  height: auto;
  margin: 2rem auto;
  border-radius: 8px;
}

/* Headings */
.article-content :deep(h2) {
  @apply font-bold text-gray-900;
  font-size: clamp(1.5rem, 4vw, 2rem);
  margin-top: clamp(2rem, 5vw, 2.5rem);
  margin-bottom: clamp(1rem, 2vw, 1.5rem);
  line-height: 1.3;
}

.article-content :deep(h3) {
  @apply font-semibold text-gray-900;
  font-size: clamp(1.25rem, 3vw, 1.5rem);
  margin-top: clamp(1.5rem, 4vw, 2rem);
  margin-bottom: clamp(0.75rem, 1.5vw, 1rem);
  line-height: 1.4;
}

.article-content :deep(h4) {
  @apply font-semibold text-gray-900;
  font-size: clamp(1.125rem, 2.5vw, 1.25rem);
  margin-top: clamp(1rem, 3vw, 1.5rem);
  margin-bottom: clamp(0.5rem, 1vw, 0.75rem);
  line-height: 1.4;
}

/* Paragraphs - justified */
.article-content :deep(p) {
  @apply text-gray-700;
  text-align: justify !important;
  margin-bottom: clamp(1rem, 2vw, 1.5rem);
  font-size: inherit;
  line-height: inherit;
}

/* Lists - remove bullets, gap, justify */
.article-content :deep(ul),
.article-content :deep(ol) {
  list-style: none !important;
  margin: 0 !important;
  padding: 0 !important;
}

.article-content :deep(li) {
  text-align: justify !important;
  margin: 0 !important;
  padding: 0 !important;
  text-indent: 0 !important;
}

/* Remove marker completely for safety */
.article-content :deep(li)::marker {
  content: none !important;
}

/* RTL list alignment fix */
[dir="rtl"] .article-content :deep(ul),
[dir="rtl"] .article-content :deep(ol) {
  padding-right: 0 !important;
}

/* LTR list alignment fix */
[dir="ltr"] .article-content :deep(ul),
[dir="ltr"] .article-content :deep(ol) {
  padding-left: 0 !important;
}

/* Blockquotes */
.article-content :deep(blockquote) {
  @apply border-blue-500 italic text-gray-700 bg-gray-50;
  border-inline-start-width: 4px;
  padding: clamp(1rem, 2vw, 1.5rem);
  margin: clamp(1rem, 2vw, 2rem) 0;
  font-size: 1.05em;
}

/* Inline code */
.article-content :deep(code) {
  @apply bg-gray-100 px-2 py-1 rounded font-mono text-gray-800;
  font-size: 0.9em;
}

/* Code blocks */
.article-content :deep(pre) {
  @apply bg-gray-800 text-white rounded-lg overflow-x-auto;
  padding: clamp(1rem, 2vw, 1.5rem);
  margin: clamp(1rem, 2vw, 2rem) 0;
  font-size: 0.875em;
}

.article-content :deep(pre code) {
  @apply bg-transparent px-0 py-0 text-white;
}

/* Strong text */
.article-content :deep(strong) {
  @apply font-bold text-gray-900;
}

/* Italics */
.article-content :deep(em) {
  @apply italic;
}

/* Links */
.article-content :deep(a) {
  @apply text-blue-600 hover:text-blue-800 underline;
  font-size: inherit;
}
</style>
