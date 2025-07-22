import { createI18n } from 'vue-i18n'
import en from './locales/en.json'
import ar from './locales/ar.json'
import ku from './locales/ku.json'

const messages = {
  en,
  ar,
  ku
}

const i18n = createI18n({
  legacy: false, // Use Composition API mode
  locale: 'ku', // Default locale (will be overridden by app)
  fallbackLocale: 'ku',
  messages,
  globalInjection: true // Allow $t to be used in templates
})

export default i18n