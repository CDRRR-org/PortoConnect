<template>
  <div class="proximity-wrap" @mousemove="onMove" @mouseleave="onLeave" @touchstart.prevent="">
    <div
      v-for="(card, i) in cards"
      :key="i"
      ref="boxes"
      class="pbox"
      :class="{ active: isActive(i) }"
      :style="boxStyle(i)"
      @mouseenter="forceOn(i)"
      @mouseleave="forceOff(i)"
      @click="toggleTouch(i)"
      role="button"
      tabindex="0"
      aria-label="Open card"
    >
      <div class="pbox-inner">
        <h3 class="pbox-title">{{ card.title }}</h3>
        <p class="pbox-desc">{{ card.text }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, onBeforeUnmount, nextTick } from 'vue'

const cards = [
  {
    title: 'Porto Connect',
    text: 'Kumpulkan semua karya dan pencapaian terbaikmu dalam satu portofolio digital yang profesional dan mudah diakses oleh recruiter.'
  },
  {
    title: 'Porto Connect',
    text: 'Buat portofolio rapi, tunjukkan project terbaikmu, dan tingkatkan peluang dipanggil interview.'
  },
  {
    title: 'Porto Connect',
    text: 'Atur showcase, tambahkan detail teknis, dan bagikan link portofolio kepada perekrut.'
  }
]

const boxes = ref([])
const state = reactive(cards.map(() => ({
  strength: 0,
  target: 0,
  forced: false,
  touchOpen: false
})))

const THRESHOLD = 180
const MAX_TRANSLATE = -18
const MAX_SCALE = 1.07
const SMOOTH = 0.14
const NEIGHBOR_BOOST = 0.48
let raf = null

onMounted(() => { nextTick(() => {}) })
onBeforeUnmount(() => cancelAnimationFrame(raf))

function onMove(e) {
  const mx = e.clientX; const my = e.clientY
  boxes.value.forEach((el, idx) => {
    if (!el) return
    const r = el.getBoundingClientRect()
    const cx = r.left + r.width / 2
    const cy = r.top + r.height / 2
    const dist = Math.hypot(mx - cx, my - cy)
    const normalized = Math.max(0, Math.min(1, (THRESHOLD - dist) / THRESHOLD))
    const baseTarget = state[idx].forced ? 1 : normalized
    state[idx].target = Math.max(state[idx].target || 0, baseTarget)
  })
  propagateNeighbors()
  startLoop()
}

function onLeave() {
  boxes.value.forEach((_, idx) => {
    state[idx].target = state[idx].touchOpen ? 1 : 0
    state[idx].forced = false
  })
  startLoop()
}

function forceOn(i) {
  state[i].forced = true
  state[i].target = 1
  if (i - 1 >= 0) state[i - 1].target = Math.max(state[i - 1].target || 0, NEIGHBOR_BOOST)
  if (i + 1 < state.length) state[i + 1].target = Math.max(state[i + 1].target || 0, NEIGHBOR_BOOST)
  startLoop()
}
function forceOff(i) {
  state[i].forced = false
  state[i].target = state[i].touchOpen ? 1 : 0
  startLoop()
}

function toggleTouch(i) {
  state[i].touchOpen = !state[i].touchOpen
  state[i].target = state[i].touchOpen ? 1 : 0
  if (state[i].touchOpen) {
    if (i - 1 >= 0) state[i - 1].target = Math.max(state[i - 1].target || 0, NEIGHBOR_BOOST * 0.9)
    if (i + 1 < state.length) state[i + 1].target = Math.max(state[i + 1].target || 0, NEIGHBOR_BOOST * 0.9)
  }
  startLoop()
}

function propagateNeighbors() {
  for (let i = 0; i < state.length; i++) {
    if (state[i].forced) {
      if (i - 1 >= 0) state[i - 1].target = Math.max(state[i - 1].target || 0, NEIGHBOR_BOOST)
      if (i + 1 < state.length) state[i + 1].target = Math.max(state[i + 1].target || 0, NEIGHBOR_BOOST)
    }
  }
}

function startLoop() {
  if (raf) return
  const step = () => {
    let running = false
    for (let i = 0; i < state.length; i++) {
      const cur = state[i].strength
      const tgt = state[i].target || 0
      const next = cur + (tgt - cur) * SMOOTH
      state[i].strength = Math.abs(next) < 0.0001 ? 0 : next
      if (Math.abs(next - tgt) > 0.001) running = true
    }
    if (running) raf = requestAnimationFrame(step)
    else raf = null
  }
  raf = requestAnimationFrame(step)
}

function isActive(i) { return state[i].strength > 0.02 }

function boxStyle(i) {
  const s = state[i].strength
  const translateY = Math.round(MAX_TRANSLATE * s)
  const scale = 1 + (MAX_SCALE - 1) * s
  const shadowY = Math.round(6 + 30 * s)
  const blur = Math.round(18 + 30 * s)
  const opacity = 0.10 + 0.4 * s
  return {
    transform: `translateY(${translateY}px) scale(${scale})`,
    boxShadow: `0 ${shadowY}px ${blur}px rgba(6,6,10,${opacity})`,
    transition: 'transform 220ms cubic-bezier(.2,.9,.25,1), box-shadow 220ms ease'
  }
}
</script>

<style scoped>
.proximity-wrap{
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
  max-width: 1200px;
  margin: 48px auto;
  padding: 0 20px;
}
.pbox{
  position: relative;
  border-radius: 12px;
  cursor: pointer;
  min-height: 180px;
  display: flex;
  align-items: stretch;
  justify-content: center;
  will-change: transform, box-shadow;
  background: transparent;
  outline: none;
}
.pbox::before{
  content: "";
  position: absolute;
  inset: 0;
  z-index: 0;
  border-radius: 14px;
  background: linear-gradient(#4b1a72,#4b1a72);
  transform: translateY(8px);
}
.pbox-inner{
  position: relative;
  z-index: 2;
  width: 100%;
  border-radius: 10px;
  padding: 28px 24px;
  margin: 10px;
  background: #fff;
  box-shadow: 0 8px 14px rgba(6,6,10,0.10);
  display: flex;
  flex-direction: column;
  gap: 12px;
  pointer-events: none;
}
.pbox-inner::after{
  content: "";
  position: absolute;
  inset: 0;
  border-radius: 10px;
  z-index: 1;
  pointer-events: none;
  box-shadow: inset 0 0 0 6px rgba(42,8,48,0.12);
}
.pbox-title{
  font-family: 'Poppins', sans-serif;
  font-size: 22px;
  margin: 0;
  font-weight: 700;
  color: #000000;
}
.pbox-desc{
  font-size: 14.5px;
  margin: 0;
  color: #222;
  line-height: 1.55;
}
.pbox.active::before{
  transform: translateY(2px) scale(1.01);
  box-shadow: 0 18px 30px rgba(75,26,114,0.12);
}
.pbox:hover .pbox-inner{ box-shadow: 0 12px 22px rgba(6,6,10,0.14); }
@media (max-width: 900px){
  .proximity-wrap{ grid-template-columns: 1fr; gap: 20px; padding: 0 14px; }
  .pbox-inner{ padding: 20px; min-height: 140px; margin: 8px; }
  .pbox-title{ font-size: 18px; }
  .pbox-desc{ font-size: 14px; }
}
</style>


