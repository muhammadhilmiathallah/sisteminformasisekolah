<template>
  <div class="p-6">
    <Toast />

    <div class="bg-white shadow-md rounded-lg p-6">
      <h2 class="text-2xl font-semibold mb-4 text-gray-800">Kritik dan Saran</h2>
      <Button label="Input Kritik dan Saran" icon="pi pi-plus" @click="showDialog = true" class="mb-4" />

<!-- Dialog Input -->
<Dialog v-model:visible="showDialog" header="📝 Form Kritik & Saran" :modal="true" class="custom-dialog rounded-xl">
  <div class="space-y-4 px-4 py-2">
    <!-- Judul -->
    <div class="form-group">
      <label for="judul">
        <i class="pi pi-book icon" /> Judul
      </label>
      <InputText id="judul" v-model="form.judul" placeholder="Masukkan judul" class="w-full" />
    </div>

    <!-- Isi -->
    <div class="form-group">
      <label for="isi">
        <i class="pi pi-comment icon" /> Isi Kritik & Saran
      </label>
      <Textarea id="isi" v-model="form.isi" placeholder="Tulis kritik atau saran Anda di sini..." rows="5" class="w-full" />
    </div>

    <!-- Tombol -->
    <div class="flex justify-end gap-2 pt-2">
      <Button label="Batal" severity="secondary" @click="resetForm" class="p-button-outlined" />
      <Button label="Simpan" icon="pi pi-save" @click="submitForm" />
    </div>
  </div>
</Dialog>




      <!-- Dialog Detail -->
 <Dialog
  v-model:visible="viewDialog"
  header="Detail Kritik dan Saran"
  :modal="true"
  class="view-kritik-dialog w-full max-w-[90vw] xl:max-w-[1100px] rounded-xl shadow-lg"
>
  <!-- Row: Judul & Tanggal -->
  <div class="field-row">
    <div class="field-box horizontal">
      <i class="pi pi-book icon blue" />
      <div>
        <p class="label">Judul:</p>
        <p>{{ selectedItem?.judul }}</p>
      </div>
    </div>
    <div class="field-box horizontal">
      <i class="pi pi-calendar icon purple" />
      <div>
        <p class="label">Tanggal:</p>
        <p>{{ selectedItem?.tanggal }}</p>
      </div>
    </div>
  </div>

  <!-- Row: Isi Kritik & Tanggapan -->
  <div class="field-row">
    <div class="field-box horizontal">
      <i class="pi pi-comments icon green" />
      <div>
        <p class="label">Isi Kritik & Saran:</p>
        <p>{{ selectedItem?.isi }}</p>
      </div>
    </div>
    <div class="field-box horizontal">
      <i class="pi pi-reply icon indigo" />
      <div>
        <p class="label">Tanggapan Guru:</p>
        <p>{{ selectedItem?.tanggapan || '-' }}</p>
      </div>
    </div>
  </div>

  <!-- Opsional: Guru Tujuan (di-comment) -->
  <!--
  <div class="field-box horizontal">
    <i class="pi pi-users icon orange" />
    <div>
      <p class="label">Guru Tujuan:</p>
      <p>{{ selectedItem?.gurus?.nama || '-' }}</p>
    </div>
  </div>
  -->

  <template #footer>
    <Button
      label="Tutup"
      icon="pi pi-times"
      @click="viewDialog = false"
      class="p-button-secondary"
    />
  </template>
</Dialog>



    <DataTable
  :value="kritikSaranList"
  class="mt-6"
  responsiveLayout="scroll"
  stripedRows
  sortField="tanggal"
  :sortOrder="-1"
>
  <Column field="no" header="No" style="width: 50px" />
  <Column field="tanggal" header="Tanggal" />
  <Column field="judul" header="Judul" />
  <Column field="isi" header="Kritik">
    <template #body="slotProps">
      <div class="wrap-cell">
        {{ slotProps.data.isi }}
      </div>
    </template>
  </Column>
  <!-- <Column header="Siswa">
    <template #body="slotProps">
      {{ slotProps.data.siswa?.nama_lengkap_siswa || '-' }}
    </template>
  </Column> -->
  <Column field="tanggapan" header="Tanggapan">
    <template #body="slotProps">
      <div class="wrap-cell">
        {{ slotProps.data.tanggapan || '-' }}
      </div>
    </template>
  </Column>
  <Column header="Guru">
    <template #body="slotProps">
      {{ slotProps.data.guru?.nama || '-' }}
    </template>
  </Column>
  <Column header="Aksi" style="width: 100px">
    <template #body="slotProps">
      <Button
        label="View"
        icon="pi pi-eye"
        size="small"
        @click="viewDetail(slotProps.data)"
        class="p-button-text p-button-sm"
      />
    </template>
  </Column>
</DataTable>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

const toast = useToast()
const token = localStorage.getItem('access_token')

const showDialog = ref(false)
const viewDialog = ref(false)
const selectedItem = ref(null)

const form = ref({
  judul: '',
  isi: '',
  guru_id: null,
  siswa_id: null
})

const kritikSaranList = ref([])
const guruList = ref([])
const siswaList = ref([])

const fetchWaliKelas = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/guru/wali-kelas', {
      headers: { Authorization: `Bearer ${token}` }
    });
    if (!response.data || !response.data.data || !response.data.data.id) {
      throw new Error('Data wali kelas tidak valid')
    }
    guruList.value = [response.data.data]
    form.value.guru_id = response.data.data.id
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal ambil wali kelas', life: 3000 })
  }
}

const fetchSiswaLogin = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/siswa/me', {
      headers: { Authorization: `Bearer ${token}` }
    })
    const siswa = response.data.data
    siswaList.value = [{ id: siswa.id, nama_lengkap_siswa: siswa.nama_lengkap_siswa }]
    form.value.siswa_id = siswa.id
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal mengambil data siswa', life: 3000 })
  }
}

const fetchData = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/kritik-saran', {
      headers: { Authorization: `Bearer ${token}` }
    })
    kritikSaranList.value = response.data.data.map((item, index) => ({
      ...item,
      no: index + 1
    }))
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal mengambil data kritik dan saran', life: 3000 })
  }
}

const formatDate = (date) => {
  const d = new Date(date)
  const month = String(d.getMonth() + 1).padStart(2, '0')
  const day = String(d.getDate()).padStart(2, '0')
  const year = d.getFullYear()
  return `${year}-${month}-${day}`
}

const submitForm = async () => {
  if (!form.value.judul || !form.value.isi || !form.value.guru_id) {
    toast.add({ severity: 'warn', summary: 'Peringatan', detail: 'Semua field harus diisi.', life: 3000 })
    return
  }

  try {
    const payload = {
      tanggal: formatDate(new Date()),
      judul: form.value.judul,
      isi: form.value.isi,
      siswa_id: form.value.siswa_id,
      guru_id: form.value.guru_id
    }

    await axios.post('http://127.0.0.1:8000/api/kritik-saran/kirim', payload, {
      headers: { Authorization: `Bearer ${token}` }
    })

    toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Data berhasil disimpan.', life: 3000 })
    resetForm()
    fetchData()
  } catch (error) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal menyimpan data', life: 3000 })
  }
}

const resetForm = () => {
  const siswa_id = form.value.siswa_id
  form.value = {
    judul: '',
    isi: '',
    guru_id: null,
    siswa_id
  }
  showDialog.value = false
}

const viewDetail = (item) => {
  selectedItem.value = item
  viewDialog.value = true
}

onMounted(() => {
  fetchWaliKelas()
  fetchSiswaLogin()
  fetchData()
})
</script>


<style scoped>
/* ============================= */
/* TABEL & KONTEN RESPONSIF */
/* ============================= */
.truncate-column {
  max-width: 250px;
  white-space: normal;
  word-break: break-word;
  overflow-wrap: break-word;
}

.p-datatable .p-column-body {
  white-space: normal !important;
  word-break: break-word;
}

@media (max-width: 768px) {
  .truncate-column {
    max-width: 100%;
  }
}

/* ============================= */
/* DIALOG STYLE */
/* ============================= */
.p-dialog .p-dialog-content {
  padding: 1.5rem !important;
}

.p-dialog .p-dialog-header {
  padding: 1rem 1.5rem !important;
  background-color: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
}

.p-dialog .p-dialog-footer {
  padding: 1rem 1.5rem !important;
  background-color: #f9fafb;
  border-top: 1px solid #e5e7eb;
}

/* ============================= */
/* FORM LAYOUT */
/* ============================= */
.p-fluid > * {
  margin-bottom: 1rem;
}

label {
  font-size: 0.875rem;
  font-weight: 500;
  margin-bottom: 0.25rem;
  display: inline-block;
  color: #374151;
}

input, textarea, .p-dropdown {
  border-radius: 0.5rem;
}

/* ============================= */
/* DIALOG DETAIL - FLEX BOXES */
/* ============================= */
.view-kritik-dialog {
  width: 100% !important;
  max-width: 1200px !important;
  min-width: 768px;
}

.field-row {
  display: flex;
  gap: 1.5rem;
  margin-bottom: 1rem;
  flex-wrap: wrap;
}

.field-box {
  flex: 1 1 48%;
  display: flex;
  gap: 1rem;
  align-items: flex-start;
  background-color: #f9fafb;
  border: 1px solid #e5e7eb;
  padding: 1rem;
  border-radius: 0.75rem;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
  min-height: 80px;
  word-break: break-word;
  white-space: pre-wrap;
}

.field-box.horizontal {
  flex-direction: row;
}

.field-box.vertical {
  flex-direction: column;
}

.field-box .icon {
  font-size: 1.5rem;
  margin-top: 0.25rem;
}

.label {
  font-weight: 600;
  font-size: 0.95rem;
  color: #374151;
  margin-bottom: 0.25rem;
}

.icon.blue { color: #3b82f6; }
.icon.purple { color: #8b5cf6; }
.icon.pink { color: #ec4899; }
.icon.orange { color: #f97316; }
.icon.green { color: #10b981; }
.icon.indigo { color: #6366f1; }

/* RESPONSIVE ADJUSTMENT */
@media (max-width: 768px) {
  .field-row {
    flex-direction: column;
  }

  .field-box {
    flex: 1 1 100%;
  }

  .p-dialog .p-dialog-content {
    max-height: 70vh;
  }
}
/* Kolom Kritik dan Tanggapan dibatasi lebarnya & wrap ke bawah */
.wrap-cell {
  max-width: 220px;
  white-space: pre-wrap;
  word-wrap: break-word;
  word-break: break-word;
  overflow-wrap: break-word;
  line-height: 1.4;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.icon {
  font-size: 1rem;
  color: #6366f1;
}

.custom-dialog {
  max-width: 550px !important;
  width: 100%;
  border-radius: 12px;
}

.p-dialog .p-dialog-content {
  padding: 1rem !important;
}

.p-dialog .p-dialog-header {
  background-color: #f9fafb;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid #e5e7eb;
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
}

.p-dialog .p-dialog-footer {
  padding: 1rem 1.25rem;
  background-color: #f9fafb;
  border-top: 1px solid #e5e7eb;
}

</style>
