<template>
  <div class="p-4 grid gap-4">
    <!-- Judul Pengumuman -->
    <h3 class="text-lg font-bold text-yellow-700">📢 PENGUMUMAN:</h3>

    <!-- Box Pengumuman Bergerak -->
    <div class="announcement-box mb-4">
      <marquee class="announcement-text" behavior="scroll" direction="left" scrollamount="5">
        {{ pengumuman }}
      </marquee>
    </div>

    <br/>

    <!-- Kartu Jumlah Siswa dan Kritik -->
    <div class="flex gap-4">
      <Card class="flex-1">
        <template #title>JUMLAH SISWA ANDA</template>
        <template #content>
          <h1 class="text-3xl font-bold text-center">{{ jumlahSiswa }}</h1>
        </template>
      </Card>
      <br/>
      <Card class="flex-1">
        <template #title>TOTAL KRITIK SARAN</template>
        <template #content>
          <h1 class="text-3xl font-bold text-center">{{ totalKritik }}</h1>
        </template>
      </Card>
    </div>
    <br/>
    <!-- Grafik -->
    <div class="grid grid-cols-2 gap-4">
      <Card>
        <template #title>Statistik Jumlah Kritik Saran Per Bulan</template>
        <template #content>
          <Chart type="bar" :data="chartData" :options="chartOptions" style="height: 300px;" />
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Card from 'primevue/card'
import Chart from 'primevue/chart'
import axios from 'axios'

const token = localStorage.getItem('access_token')
const headers = { Authorization: `Bearer ${token}` }

const jumlahSiswa = ref(0)
const totalKritik = ref(0)
const pengumuman = ref('-')

const chartData = ref({
  labels: [],
  datasets: [
    {
      label: 'Jumlah Kritik',
      backgroundColor: '#708090',
      data: []
    }
  ]
})

const chartOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      labels: {
        color: '#333'
      }
    }
  },
  scales: {
    x: {
      ticks: { color: '#333' },
      grid: { color: '#eee' }
    },
    y: {
      ticks: { color: '#333' },
      grid: { color: '#eee' }
    }
  }
})

const fetchData = async () => {
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/dashboard-guru/stats', { headers })

    jumlahSiswa.value = res.data.jumlah_siswa ?? 0
    totalKritik.value = res.data.total_kritik ?? 0

    chartData.value.labels = res.data.kritik_perbulan?.labels ?? []
    chartData.value.datasets[0].data = res.data.kritik_perbulan?.values ?? []

    pengumuman.value = res.data.pengumuman ?? '-'
  } catch (err) {
    console.error('Gagal memuat data dashboard guru:', err)
  }
}

onMounted(fetchData)
</script>

<style scoped>
.announcement-box {
  background-color: #fff9c4;
  border: 2px solid #facc15;
  border-radius: 10px;
  padding: 10px;
  overflow: hidden;
}

.announcement-text {
  font-size: 1.2rem;
  font-weight: 600;
  color: #b45309;
  white-space: nowrap;
}

.card {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  border-radius: 12px;
  padding: 1rem;
}
</style>
