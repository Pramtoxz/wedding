// ============ DATE & TIME HELPERS FOR VINTAGE MINANG ============

/**
 * Parse a date string (e.g. "2026-08-15" or "2026-08-15 10:00:00") into
 * { year, month (1-12), day } WITHOUT timezone shifting.
 * Date-only strings (no time part) are treated as LOCAL date, not UTC.
 */
function parseDateParts(date: string): { year: number; month: number; day: number; d: Date } {
  const dateOnly = date.split(/[ T]/)[0]           // "2026-08-15"
  const [year, month, day] = dateOnly.split('-').map(Number)
  // Construct with explicit local time to avoid UTC-to-local shift
  const d = new Date(year, month - 1, day)
  return { year, month, day, d }
}

export function formatDateDots(date: string): string {
  if (!date) return ''
  const { year, month, day } = parseDateParts(date)
  const pad = (n: number) => String(n).padStart(2, '0')
  return `${pad(day)} . ${pad(month)} . ${year}`
}

export function formatDayName(date: string): string {
  if (!date) return ''
  const { d } = parseDateParts(date)
  return d.toLocaleDateString('id-ID', { weekday: 'long' })
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
