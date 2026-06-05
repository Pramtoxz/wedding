// ============ DATE & TIME HELPERS FOR VINTAGE MINANG ============

export function formatDateDots(date: string): string {
  if (!date) return ''
  const safeDate = date.replace(' ', 'T')
  const d = new Date(safeDate)
  const pad = (n: number) => String(n).padStart(2, '0')
  return `${pad(d.getDate())} . ${pad(d.getMonth() + 1)} . ${d.getFullYear()}`
}

export function formatDayName(date: string): string {
  if (!date) return ''
  const safeDate = date.replace(' ', 'T')
  return new Date(safeDate).toLocaleDateString('id-ID', { weekday: 'long' })
}

export function timeAgo(dateStr: string): string {
  if (!dateStr) return ''
  const safeDate = dateStr.replace(' ', 'T')
  const diff = Date.now() - new Date(safeDate).getTime()
  const m = Math.floor(diff / 60000)
  const h = Math.floor(diff / 3600000)
  const d = Math.floor(diff / 86400000)
  if (m < 1) return 'baru saja'
  if (m < 60) return `${m} menit lalu`
  if (h < 24) return `${h} jam lalu`
  return `${d} hari lalu`
}
