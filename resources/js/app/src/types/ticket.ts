interface Comment {
    id: number
    user_name: string
    text: string
    created_at: string
}

export interface Ticket {
    id: number
    title: string
    description: string
    status: string
    created_at: string
    updated_at: string
    assigned: string
    comments: Comment[]
}
