<template>
  <div
    class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow cursor-pointer transform hover:scale-105"
    @click="$emit('select', portfolio)"
  >
    <!-- Header -->
    <div class="h-32 bg-gradient-to-br from-purple-500 via-pink-500 to-blue-500 relative">
      <div class="absolute inset-0 bg-black/20"></div>
      <div class="absolute bottom-4 left-4 right-4">
        <h3 class="text-white font-bold text-lg">{{ portfolio.nama || 'Portofolio' }}</h3>
      </div>
    </div>

    <!-- Content -->
    <div class="p-4">
      <div class="flex items-start gap-3 mb-3">
        <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
          {{ portfolio.mahasiswa?.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
        </div>
        <div class="flex-1 min-w-0">
          <h4 class="font-bold text-gray-800 truncate">{{ portfolio.mahasiswa?.user?.name || 'Mahasiswa' }}</h4>
          <p class="text-sm text-gray-600">
            {{ portfolio.mahasiswa?.universitas || portfolio.mahasiswa?.jurusan || '-' }}
          </p>
        </div>
        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
        </svg>
      </div>

      <div v-if="portfolio.bidang" class="mb-3">
        <span class="inline-block bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
          {{ portfolio.bidang }}
        </span>
      </div>

      <p v-if="portfolio.deskripsi" class="text-sm text-gray-600 mb-3 line-clamp-2">
        {{ portfolio.deskripsi }}
      </p>

      <div v-if="portfolio.skills && portfolio.skills.length > 0" class="flex flex-wrap gap-2">
        <span
          v-for="skill in portfolio.skills.slice(0, 3)"
          :key="skill.id"
          class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"
        >
          {{ skill.nama }}
        </span>
        <span v-if="portfolio.skills.length > 3" class="text-xs text-gray-500">
          +{{ portfolio.skills.length - 3 }} lainnya
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  portfolio: {
    type: Object,
    required: true,
  },
})

defineEmits(['select'])
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>


