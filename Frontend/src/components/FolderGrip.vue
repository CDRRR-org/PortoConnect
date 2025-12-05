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
      <!-- purple frame is created by pseudo ::before, keep inner content above it -->
      <div class="pbox-inner" aria-hidden="false">
        <h3 class="pbox-title">{{ card.title }}</h3>
        <p class="pbox-desc">{{ card.text }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, onBeforeUnmount, nextTick } from 'vue'

// cards content (3 boxes)
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

// refs for DOM elements
const boxes = ref([])

// per-box reactive state
const state = reactive(cards.map(()=>({
  strength: 0,   // current displayed 0..1
  target: 0,     // target 0..1
  forced: false, // mouseenter full
  touchOpen: false
})))

// parameters (tweakable)
const THRESHOLD = 180         // px radius for proximity influence
const MAX_TRANSLATE = -18     // px (negative = move up)
const MAX_SCALE = 1.07
const SMOOTH = 0.14          // smoothing for RAF lerp
const NEIGHBOR_BOOST = 0.48  // neighbor forced target when a box is forced
let raf = null

// ensure boxes ref array is updated after render
onMounted(()=>{ nextTick(()=> {}) })
onBeforeUnmount(()=> cancelAnimationFrame(raf))

// mouse move handler: compute normalized proximity per box
function onMove(e){
  const mx = e.clientX, my = e.clientY
  boxes.value.forEach((el, idx)=>{
    if (!el) return
    const r = el.getBoundingClientRect()
    const cx = r.left + r.width/2
    const cy = r.top + r.height/2
    const dist = Math.hypot(mx - cx, my - cy)
    const normalized = Math.max(0, Math.min(1, (THRESHOLD - dist) / THRESHOLD))
    // if forced by direct enter, keep forced
    const baseTarget = state[idx].forced ? 1 : normalized
    state[idx].target = Math.max(state[idx].target || 0, baseTarget) // allow increase immediately
  })
  propagateNeighbors() // propagate neighbor boost if any forced
  startLoop()
}

// when leaving container => drop targets (except touched ones)
function onLeave(){
  boxes.value.forEach((_, idx)=>{
    state[idx].target = state[idx].touchOpen ? 1 : 0
    state[idx].forced = false
  })
  startLoop()
}

// mouseenter: full pop; also give neighbors partial boost
function forceOn(i){
  state[i].forced = true
  state[i].target = 1
  // neighbor boost
  if (i-1 >= 0) state[i-1].target = Math.max(state[i-1].target || 0, NEIGHBOR_BOOST)
  if (i+1 < state.length) state[i+1].target = Math.max(state[i+1].target || 0, NEIGHBOR_BOOST)
  startLoop()
}
function forceOff(i){
  state[i].forced = false
  // when leaving element, reduce its target (unless touchOpen)
  state[i].target = state[i].touchOpen ? 1 : 0
  // neighbors will fade via normal loop
  startLoop()
}

// click/tap toggles for mobile
function toggleTouch(i){
  state[i].touchOpen = !state[i].touchOpen
  state[i].target = state[i].touchOpen ? 1 : 0
  // when opening by touch, give neighbors a small boost for feel
  if (state[i].touchOpen){
    if (i-1 >= 0) state[i-1].target = Math.max(state[i-1].target || 0, NEIGHBOR_BOOST * 0.9)
    if (i+1 < state.length) state[i+1].target = Math.max(state[i+1].target || 0, NEIGHBOR_BOOST * 0.9)
  }
  startLoop()
}

// if any forced box exists, also propagate a soft influence outward (so nearby boxes slightly rise)
// this is additive to proximity calculation (we only set targets here)
function propagateNeighbors(){
  for (let i=0;i<state.length;i++){
    if (state[i].forced){
      if (i-1 >= 0) state[i-1].target = Math.max(state[i-1].target || 0, NEIGHBOR_BOOST)
      if (i+1 < state.length) state[i+1].target = Math.max(state[i+1].target || 0, NEIGHBOR_BOOST)
    }
  }
}

// smoothing loop using RAF (lerp)
function startLoop(){
  if (raf) return
  const step = ()=>{
    let running = false
    for (let i=0;i<state.length;i++){
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

// helper used in template to mark active class (>= small threshold)
function isActive(i){ return state[i].strength > 0.02 }

// compute inline style for each box based on strength
function boxStyle(i){
  const s = state[i].strength
  const translateY = Math.round(MAX_TRANSLATE * s) // negative
  const scale = 1 + (MAX_SCALE - 1) * s
  const shadowY = Math.round(6 + 30 * s)
  const blur = Math.round(18 + 30 * s)
  const opacity = 0.10 + 0.4 * s
  // purple frame intensity tweak (we'll use CSS var to change frame shadow color intensity)
  return {
    transform: `translateY(${translateY}px) scale(${scale})`,
    boxShadow: `0 ${shadowY}px ${blur}px rgba(6,6,10,${opacity})`,
    transition: 'transform 220ms cubic-bezier(.2,.9,.25,1), box-shadow 220ms ease'
  }
}
</script>

<style scoped>
/* layout */
.proximity-wrap{
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
  max-width: 1200px;
  margin: 48px auto;
  padding: 0 20px;
}

/* box wrapper (relative so pseudo ::before frame can sit behind) */
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

/* PURPLE FRAME as background behind inner card (pseudo element) */
.pbox::before{
  content: "";
  position: absolute;
  inset: 0;
  z-index: 0;
  border-radius: 14px;
  /* purple solid frame: use box-shadow to create thick bezel that sits outside inner */
  background: linear-gradient(#4b1a72,#4b1a72);
  transform: translateY(8px); /* slight offset so inner looks raised above frame */
  filter: blur(0px);
  /* we will clip inner area by adding white inner panel above it */
}

/* inner white panel (content) — sits above the purple frame */
.pbox-inner{
  position: relative;
  z-index: 2;
  width: 100%;
  border-radius: 10px;
  padding: 28px 24px;
  margin: 10px; /* create visible purple border area */
  background: #fff;
  box-shadow: 0 8px 14px rgba(6,6,10,0.10);
  display: flex;
  flex-direction: column;
  gap: 12px;
  pointer-events: none; /* keep pointer events on pbox for interactions */
  border-left: 8px solid transparent; /* spacing for visual */
}

/* subtle darker inner stroke - create using inset box-shadow on .pbox-inner */
.pbox-inner::after{
  content: "";
  position: absolute;
  inset: 0;
  border-radius: 10px;
  z-index: 1;
  pointer-events: none;
  box-shadow: inset 0 0 0 6px rgba(42,8,48,0.12); /* inner thin dark line */
}

/* content text */
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

/* active visual tweak: when box has active class, emphasize frame offset and shadow */
.pbox.active::before{
  transform: translateY(2px) scale(1.01);
  box-shadow: 0 18px 30px rgba(75,26,114,0.12);
}

/* small hover hint (if user hovers directly) */
.pbox:hover .pbox-inner{ box-shadow: 0 12px 22px rgba(6,6,10,0.14); }

/* responsive: stack single column on small screens */
@media (max-width: 900px){
  .proximity-wrap{ grid-template-columns: 1fr; gap: 20px; padding: 0 14px; }
  .pbox-inner{ padding: 20px; min-height: 140px; margin: 8px; }
  .pbox-title{ font-size: 18px; }
  .pbox-desc{ font-size: 14px; }
}
</style>
