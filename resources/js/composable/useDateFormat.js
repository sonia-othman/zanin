// composables/useDateFormat.js
export function formatDate(dateString, locale, isRTL) {
  const date = new Date(dateString)

  if (isRTL) {
    if (locale === 'ar') {
      return date.toLocaleDateString('ar-SA', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      })
    } else if (locale === 'ku') {
      return date.toLocaleDateString('ku-IQ', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      })
    }
  }

  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}
