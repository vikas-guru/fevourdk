/**
 * useNgoTour(pageKey) — wires a page to its registry tour in ~2 lines.
 *
 *   const { tourRef, steps, storageKey, startTour } = useNgoTour('analytics')
 *   <DashboardTour ref="tourRef" :steps="steps" :storage-key="storageKey" auto-start />
 *
 * Auto-start (first-run, localStorage-gated) is handled by DashboardTour itself.
 * This composable also registers the page's start() into tourController so the
 * shell-mounted Help bot can replay "this page's tour" from anywhere.
 */
import { onBeforeUnmount, onMounted, ref } from 'vue'
import { getTour } from './tours'
import * as tourController from './tourController'

export function useNgoTour(pageKey) {
    const tour = getTour(pageKey)
    const tourRef = ref(null)
    const hasTour = ref(!!tour)

    function startTour() {
        tourRef.value?.start?.()
    }

    onMounted(() => {
        if (tour) {
            tourController.register(startTour)
        }
    })

    onBeforeUnmount(() => {
        tourController.clear()
    })

    return {
        tourRef,
        steps: tour?.steps ?? [],
        storageKey: tour?.storageKey ?? `ngo_tour_${pageKey}_v1`,
        hasTour,
        startTour,
    }
}
