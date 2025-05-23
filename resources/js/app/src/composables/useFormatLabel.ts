export function useFormatLabel() {
    const formatLabel = (value: string): string => {
        if (!value) return ''
        return value
            .replace(/_/g, ' ')
            .replace(/\b\w/g, c => c.toUpperCase())
    }

    return { formatLabel }
}
