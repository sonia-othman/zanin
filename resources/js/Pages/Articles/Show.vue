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
import { computed, onMounted, nextTick } from 'vue';

// Add Prism.js for syntax highlighting
import Prism from 'prismjs';
import 'prismjs/themes/prism-tomorrow.css'; // Dark theme
// Import language components you need
import 'prismjs/components/prism-markup'; // HTML
import 'prismjs/components/prism-css';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-python';
import 'prismjs/components/prism-java';
import 'prismjs/components/prism-json';
import 'prismjs/components/prism-sql';

const page = usePage();
const article = page.props.article;
const locale = computed(() => page.props.locale);
const isRTL = computed(() => locale.value === 'ar' || locale.value === 'ku');

// Apply syntax highlighting after content loads
onMounted(async () => {
  await nextTick();
  Prism.highlightAll();
});

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
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Headings */
.article-content :deep(h1) {
  @apply font-bold text-gray-900;
  font-size: clamp(1.75rem, 4.5vw, 2.5rem);
  margin-top: clamp(2.5rem, 6vw, 3rem);
  margin-bottom: clamp(1rem, 2vw, 1.5rem);
  line-height: 1.2;
}

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
  margin: clamp(1rem, 2vw, 1.5rem) 0 !important;
  padding: 0 !important;
}

.article-content :deep(li) {
  text-align: justify !important;
  margin: 0 0 clamp(0.5rem, 1vw, 0.75rem) 0 !important;
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
  border-radius: 4px;
}

/* Inline code */
.article-content :deep(code:not(pre code)) {
  @apply bg-gray-100 px-2 py-1 rounded font-mono text-gray-800;
  font-size: 0.9em;
  border: 1px solid #e5e7eb;
}

/* Code blocks - Enhanced with better styling */
.article-content :deep(pre) {
  @apply bg-gray-900 text-white rounded-lg overflow-x-auto;
  padding: clamp(1rem, 2vw, 1.5rem);
  margin: clamp(1.5rem, 3vw, 2rem) 0;
  font-size: clamp(0.875rem, 1.5vw, 0.95rem);
  line-height: 1.6;
  border: 1px solid #374151;
  position: relative;
}

.article-content :deep(pre code) {
  @apply bg-transparent px-0 py-0 text-white font-mono;
  border: none;
  font-size: inherit;
}

/* Code block language indicator */
.article-content :deep(pre[class*="language-"]::before) {
  content: attr(class);
  content: attr(data-language);
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  font-size: 0.75rem;
  color: #9ca3af;
  text-transform: uppercase;
  font-weight: 500;
}

/* Syntax highlighting enhancements */
.article-content :deep(.token.comment),
.article-content :deep(.token.prolog),
.article-content :deep(.token.doctype),
.article-content :deep(.token.cdata) {
  color: #6b7280;
}

.article-content :deep(.token.punctuation) {
  color: #d1d5db;
}

.article-content :deep(.token.property),
.article-content :deep(.token.tag),
.article-content :deep(.token.boolean),
.article-content :deep(.token.number),
.article-content :deep(.token.constant),
.article-content :deep(.token.symbol),
.article-content :deep(.token.deleted) {
  color: #f87171;
}

.article-content :deep(.token.selector),
.article-content :deep(.token.attr-name),
.article-content :deep(.token.string),
.article-content :deep(.token.char),
.article-content :deep(.token.builtin),
.article-content :deep(.token.inserted) {
  color: #34d399;
}

.article-content :deep(.token.operator),
.article-content :deep(.token.entity),
.article-content :deep(.token.url),
.article-content :deep(.language-css .token.string),
.article-content :deep(.style .token.string) {
  color: #60a5fa;
}

.article-content :deep(.token.atrule),
.article-content :deep(.token.attr-value),
.article-content :deep(.token.keyword) {
  color: #a78bfa;
}

.article-content :deep(.token.function),
.article-content :deep(.token.class-name) {
  color: #fbbf24;
}

.article-content :deep(.token.regex),
.article-content :deep(.token.important),
.article-content :deep(.token.variable) {
  color: #f59e0b;
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
  @apply text-blue-600 hover:text-blue-800 underline transition-colors;
  font-size: inherit;
}

.article-content :deep(a:hover) {
  text-decoration-thickness: 2px;
}

/* Horizontal rules */
.article-content :deep(hr) {
  @apply border-gray-300 my-8;
  border-top-width: 1px;
}

/* Tables (if you plan to support them) */
.article-content :deep(table) {
  @apply w-full border-collapse border border-gray-300;
  margin: clamp(1rem, 2vw, 2rem) 0;
  font-size: 0.95em;
}

.article-content :deep(th),
.article-content :deep(td) {
  @apply border border-gray-300 px-4 py-2 text-left;
}

.article-content :deep(th) {
  @apply bg-gray-50 font-semibold;
}

/* RTL specific adjustments */
[dir="rtl"] .article-content :deep(blockquote) {
  border-left: none;
  border-right: 4px solid theme('colors.blue.500');
}

[dir="ltr"] .article-content :deep(blockquote) {
  border-right: none;
  border-left: 4px solid theme('colors.blue.500');
}

/* Mobile responsiveness for code blocks */
@media (max-width: 640px) {
  .article-content :deep(pre) {
    margin-left: -1rem;
    margin-right: -1rem;
    border-radius: 0;
    padding: 1rem;
  }
}

/* Dark mode support (if you implement it) */
@media (prefers-color-scheme: dark) {
  .article-content :deep(code:not(pre code)) {
    @apply bg-gray-800 text-gray-200;
    border-color: #4b5563;
  }
}
</style>