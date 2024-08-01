export interface iReading {
    id: number
    type: string
    read_at: Date | string
    reading: number | string
}
export interface iLink {
    url: string,
    label: string,
    active: string,
}
export interface iReadings {
    data: Array<iReading>
    current_page: number,
    first_page_url: string | null,
    from: number,
    last_page: number,
    last_page_url: string | null,
    links: Array<iLink>,
    next_page_url: string | null,
    path: string | null,
    per_page: number,
    prev_page_url: string | null,
    to: number,
    total: number,
}
export interface iNotification {
    success?: string
    info?: string
    danger?: string
    warning?: string
}

export interface iStats {
    min: number;
    max: number;
    mean: number;
}

export interface iReading1 {
    date: string;
    type: string;
    mean_reading: number;
}
