export {}
declare global {
  export namespace models {

    export interface Faq {
      // columns
      id: number
      title: string
      content: string
      status: boolean
      created_at?: Date | null
      updated_at?: Date | null
    }

    export interface News {
      // columns
      id: number
      title: string
      slug: string
      content: string
      status: boolean
      created_at?: Date | null
      updated_at?: Date | null
    }

    export interface Notification {
      // columns
      id: number
      title: string
      content: string
      link?: string | null
      status: boolean
      all_users: boolean
      created_at?: Date | null
      updated_at?: Date | null
      // relations
      users?: User[]
    }

    export interface Server {
      // columns
      id: number
      name: string
      latin_name: string
      config: string
      traffic: number
      location: string
      status: boolean
      created_at?: Date | null
      updated_at?: Date | null
      deleted_at?: Date | null
      // relations
      users?: User[]
    }

    export interface User {
      // columns
      id: number
      name: string
      email: string
      email_verified_at?: Date | null
      password?: string
      two_factor_secret?: string | null
      two_factor_recovery_codes?: string | null
      two_factor_confirmed_at?: Date | null
      remember_token?: string | null
      current_team_id?: number | null
      profile_photo_path?: string | null
      status: boolean
      created_at?: Date | null
      updated_at?: Date | null
      // mutators
      profile_photo_url: string
      // relations
      tokens?: PersonalAccessToken[]
      roles?: Role[]
      permissions?: Permission[]
      notifications?: DatabaseNotification[]
    }

    export interface VConfig {
      // columns
      id: number
      title: string
      server_id: number
      user_id: number
      used: number
      status: boolean
      operator: string
      config_encrypted: unknown
      expire?: Date | null
      created_at?: Date | null
      updated_at?: Date | null
      // mutators
      is_expired: unknown
      // relations
      user?: User
      server?: Server
    }

  }
}
