// ============ SHARED INTERFACES FOR VINTAGE MINANG TEMPLATE ============

export interface WeddingEvent {
  id: number
  event_name: string
  event_date?: string
  event_time?: string
  location_name: string
  address?: string
  map_url?: string
  order: number
}

export interface Gallery {
  id: number
  image_path: string
  order: number
}

export interface Gift {
  id: number
  bank_name: string
  account_number: string
  account_name: string
  order: number
}

export interface Wish {
  id: number
  guest_name: string
  message: string
  attendance_status?: string
  created_at: string
}

export interface Wedding {
  id: number
  bride_name: string
  bride_father_name: string | null
  bride_mother_name: string | null
  bride_photo: string | null
  groom_name: string
  groom_father_name: string | null
  groom_mother_name: string | null
  groom_photo: string | null
  wedding_date: string
  cover_image: string | null
  cover_page_image: string | null
  opening_text: string | null
  closing_text: string | null
  love_story: string | null
  music_url: string | null
  slug: string
  events: WeddingEvent[]
  galleries: Gallery[]
  gifts: Gift[]
  wishes: Wish[]
  rsvps: any[]
}

export interface Guest {
  id: number
  name: string
  display_name?: string
  invitation_code: string
}
