import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'

export function useTranslations() {
  const { t, locale } = useI18n()
  const page = usePage()
  
  const currentLocale = computed(() => page.props.locale || 'en')
  
  // Sync i18n locale with Laravel locale
  const syncLocale = () => {
    if (locale.value !== currentLocale.value) {
      locale.value = currentLocale.value
    }
  }
  
  // Helper for database content translations
  const getTranslation = (item, field, fallback = '') => {
    // First try the direct field (set by Laravel)
    if (item[field]) {
      return item[field]
    }
    
    // Then try translation object
    if (item.translation && item.translation[field]) {
      return item.translation[field]
    }
    
    // Finally use fallback with i18n key
    return fallback || t(`common.untitled`)
  }
  
  return {
    t,
    locale,
    currentLocale,
    syncLocale,
    getTranslation
  }
}