import { useRoute } from 'vue-router'
import { computed } from 'vue'

import DefaultLayout from '@/layouts/Default.vue'
import BlankLayout from '@/layouts/Blank.vue'

const layouts = {
    default: DefaultLayout,
    blank: BlankLayout,
}

export function useLayout() {
    const route = useRoute()

    const layoutComponent = computed(() => {
        const layoutKey = route.meta.layout || 'default'
        return layouts[layoutKey] || DefaultLayout
    })

    return {
        layoutComponent,
    }
}
