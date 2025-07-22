<template>
  <div class="p-4 max-w-xl mx-auto">
    <Toast />
    <h2 class="text-xl font-bold mb-6">Cetak Laporan Penilaian Siswa</h2>

    <div class="space-y-4">
      <!-- Label + Dropdown -->
      <div>
        <label for="tahunPelajaran" class="block text-sm font-medium text-gray-700 mb-1">
          Pilih Tahun Pelajaran
        </label>
        <Dropdown
          id="tahunPelajaran"
          v-model="selectedTahun"
          :options="tahunPelajaranOptions"
          optionLabel="tahun_pelajaran"
          optionValue="id"
          placeholder="Pilih Tahun Pelajaran"
          class="w-full"
          :loading="loadingTahun"
          filter
        />
      </div>

      <!-- Tombol Cetak -->
      <div class="flex justify-end pt-2">
        <Button
          label="Cetak Laporan Rekapan Nilai"
          icon="pi pi-print"
          :disabled="!selectedTahun || !selectedKelas"
          @click="submitCetak"
          class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md shadow-sm"
        />
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import axios from 'axios';

const toast = useToast();
const token = localStorage.getItem('access_token');

const selectedTahun = ref(null);
const selectedKelas = ref(null);
const tahunPelajaranOptions = ref([]);
const kelasOptions = ref([]);
const loadingTahun = ref(false);
const loadingKelas = ref(false);

const fetchTahunPelajaran = async () => {
  if (!token) return;
  loadingTahun.value = true;
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/tahun-pelajaran', {
      headers: { Authorization: `Bearer ${token}` },
    });
    tahunPelajaranOptions.value = Array.isArray(res.data) ? res.data : [];
  } catch (err) {
    toast.add({
      severity: 'error',
      summary: 'Gagal Memuat',
      detail: 'Tidak bisa mengambil tahun pelajaran.',
      life: 3000,
    });
  } finally {
    loadingTahun.value = false;
  }
};

const fetchKelasGuru = async () => {
  if (!token) return;
  loadingKelas.value = true;
  try {
    const res = await axios.get('http://127.0.0.1:8000/api/guru/kelas-guru', {
      headers: { Authorization: `Bearer ${token}` },
    });

    const kelas = Array.isArray(res.data) ? res.data[0] : res.data;
    if (!kelas || !kelas.id) throw new Error('Kelas tidak ditemukan.');

    kelasOptions.value = [kelas];
    selectedKelas.value = kelas.id;
  } catch (err) {
    toast.add({
      severity: 'error',
      summary: 'Gagal Memuat',
      detail: err.response?.data?.message || err.message || 'Gagal mengambil kelas guru login.',
      life: 4000,
    });
  } finally {
    loadingKelas.value = false;
  }
};

const submitCetak = async () => {
  if (!selectedTahun.value || !selectedKelas.value) {
    toast.add({
      severity: 'warn',
      summary: 'Lengkapi Data',
      detail: 'Silakan pilih tahun pelajaran dan kelas.',
      life: 3000,
    });
    return;
  }

  const url = `http://127.0.0.1:8000/api/cetak-raport/${selectedTahun.value}?kelas_id=${selectedKelas.value}`;

  try {
    const res = await axios.get(url, {
      headers: { Authorization: `Bearer ${token}` },
      responseType: 'blob',
    });

    const file = new Blob([res.data], { type: 'application/pdf' });
    const fileURL = URL.createObjectURL(file);
    window.open(fileURL, '_blank');
    setTimeout(() => URL.revokeObjectURL(fileURL), 10000);
  } catch (error) {
    console.error('Cetak Error:', error);
    const detailMsg = error.response?.data?.message || error.message || 'Terjadi kesalahan';
    toast.add({
      severity: 'error',
      summary: 'Gagal Cetak',
      detail: `Gagal membuka file raport. ${detailMsg}`,
      life: 4000,
    });
  }
};

onMounted(() => {
  fetchTahunPelajaran();
  fetchKelasGuru();
});
</script>
