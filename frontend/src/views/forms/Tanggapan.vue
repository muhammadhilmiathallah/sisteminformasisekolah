<template>
  <div class="p-4">
    <Toast />
    <h2 class="text-xl font-bold mb-3">Tanggapan Kritik dan Saran</h2>

    <!-- Dialog Tanggapan -->
    <Dialog v-model:visible="showDialog" header="Tanggapi Kritik dan Saran" :modal="true" class="w-full md:w-1/2 rounded-lg">
      <div class="p-3 space-y-4">
        <!-- Field Box: Judul -->
        <div class="form-group">
          <input type="text" class="form-control peer" :value="selectedItem?.judul" placeholder=" " readonly />
          <label class="form-label">Judul</label>
        </div>

        <!-- Field Box: Isi -->
        <div class="form-group">
          <textarea class="form-control peer" rows="4" :value="selectedItem?.isi" placeholder=" " readonly></textarea>
          <label class="form-label">Isi</label>
        </div>

        <!-- Field Box: Tanggapan -->
        <div class="form-group">
          <Textarea
            v-model="tanggapan"
            id="tanggapan"
            rows="5"
            class="form-control peer"
            placeholder=" "
            autoResize
            maxlength="1000"
          />
          <label for="tanggapan" class="form-label">Tanggapan Anda</label>
          <p class="text-xs text-gray-500 text-right mt-1">{{ tanggapan.length }}/1000 karakter</p>
        </div>
      </div>

      <!-- FOOTER BUTTON -->
      <template #footer>
        <div class="flex justify-end gap-2 px-3 pb-3">
          <Button label="Batal" severity="secondary" class="p-button-outlined" @click="closeDialog" />
          <Button label="Kirim Tanggapan" icon="pi pi-check" @click="submitTanggapan" />
        </div>
      </template>
    </Dialog>

    <!-- Tabel Data Kritik Saran -->
    <DataTable
      :value="kritikSaranList"
      class="mt-4"
      responsiveLayout="scroll"
      stripedRows
    >
      <Column field="no" header="No" style="width: 50px" />
      <Column field="tanggal" header="Tanggal" />
      <Column header="Siswa">
        <template #body="slotProps">
          {{ slotProps.data.siswa?.nama_lengkap_siswa || '-' }}
        </template>
      </Column>
      <Column field="judul" header="Judul" />
      <Column field="isi" header="Isi" style="width: 250px">
        <template #body="slotProps">
          <div class="wrap-text">
            {{ slotProps.data.isi }}
          </div>
        </template>
      </Column>
      <Column header="Tanggapan" style="width: 250px">
        <template #body="slotProps">
          <div class="wrap-text">
            {{ slotProps.data.tanggapan || '-' }}
          </div>
        </template>
      </Column>
      <Column header="Aksi">
        <template #body="slotProps">
          <Button
            label="Tanggapi"
            icon="pi pi-reply"
            size="small"
            @click="openDialog(slotProps.data)"
            :disabled="!!slotProps.data.tanggapan"
          />
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import Textarea from 'primevue/textarea'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

const toast = useToast()
const token = localStorage.getItem('access_token')

const showDialog = ref(false)
const selectedItem = ref(null)
const tanggapan = ref('')
const kritikSaranList = ref([])

const fetchKritikSaran = async () => {
  try {
    const res = await axios.get(`http://127.0.0.1:8000/api/kritik-saran/guru`, {
      headers: { Authorization: `Bearer ${token}` }
    })

    // Sort dari tanggal terbaru ke paling lama (DESCENDING)
    const sortedData = res.data.data.sort((a, b) => new Date(b.tanggal) - new Date(a.tanggal))

    kritikSaranList.value = sortedData.map((item, index) => ({
      ...item,
      no: index + 1
    }))
  } catch (err) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal ambil data kritik dan saran', life: 3000 })
  }
}

const openDialog = (item) => {
  selectedItem.value = item
  tanggapan.value = item.tanggapan || ''
  showDialog.value = true
}

const closeDialog = () => {
  showDialog.value = false
  selectedItem.value = null
  tanggapan.value = ''
}

const submitTanggapan = async () => {
  if (!tanggapan.value) {
    toast.add({ severity: 'warn', summary: 'Peringatan', detail: 'Tanggapan tidak boleh kosong.', life: 3000 })
    return
  }

  try {
    await axios.post(
      `http://127.0.0.1:8000/api/kritik-saran/${selectedItem.value.id}/tanggapan`,
      { tanggapan: tanggapan.value },
      { headers: { Authorization: `Bearer ${token}` } }
    )
    toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Tanggapan berhasil dikirim.', life: 3000 })
    closeDialog()
    fetchKritikSaran()
  } catch (err) {
    toast.add({ severity: 'error', summary: 'Error', detail: 'Gagal mengirim tanggapan', life: 3000 })
  }
}

onMounted(() => {
  fetchKritikSaran()
})
</script>

<style scoped>
.truncate-text {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.wrap-text {
  white-space: pre-wrap;
  word-break: break-word;
  overflow-wrap: break-word;
}

textarea {
  resize: vertical;
  transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

textarea:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 1px #3b82f6;
}

.form-group {
  position: relative;
  margin-top: 1.5rem;
}

.form-control {
  width: 100%;
  padding: 1rem 0.75rem 0.25rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  background-color: #f9fafb;
  font-size: 0.95rem;
  transition: all 0.2s ease;
  color: #111827;
  resize: none;
}

.form-control:focus {
  outline: none;
  border-color: #3b82f6;
  background-color: white;
  box-shadow: 0 0 0 1px #3b82f6;
}

.form-label {
  position: absolute;
  left: 0.75rem;
  top: 1rem;
  background: white;
  color: #6b7280;
  font-size: 0.875rem;
  padding: 0 0.25rem;
  transition: all 0.2s ease;
  pointer-events: none;
}

.form-control:focus + .form-label,
.form-control:not(:placeholder-shown) + .form-label {
  top: -0.5rem;
  font-size: 0.75rem;
  color: #2563eb;
}
</style>
