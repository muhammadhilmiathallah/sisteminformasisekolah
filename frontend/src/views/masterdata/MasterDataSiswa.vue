<template>
  <div class="siswa-container">
    <div class="header">
      <h2>Daftar Siswa</h2>
      <InputText v-model="searchQuery" class="p-inputtext-sm" placeholder="Cari Nama Siswa, NIS dan NISN" style="float: right; width: 245px; margin-bottom: 20px" />
    </div>

    <DataTable :value="filteredSiswaList" :paginator="true" :rows="10" responsiveLayout="scroll">
      <Column field="nama_lengkap_siswa" header="Nama" />
      <Column field="nama_kelas" header="Kelas" />
      <Column field="nis" header="NIS" />
      <Column field="nisn" header="NISN" />
      <Column field="tempat_lahir" header="Tempat Lahir" />
      <Column field="tanggal_lahir" header="Tanggal Lahir" />
      <Column field="email" header="Email" />

      <Column header="Aksi" style="width: 150px">
        <template #body="slotProps">
          <div class="action-buttons">
            <Button icon="pi pi-eye" class="p-button-rounded p-button-info p-button-sm" @click="viewSiswa(slotProps.data.id)" />
            <Button icon="pi pi-pencil" class="p-button-rounded p-button-warning p-button-sm" @click="editSiswa(slotProps.data.id)" />
            <Button icon="pi pi-trash" class="p-button-rounded p-button-danger p-button-sm" @click="deleteSiswa(slotProps.data.id)" />
          </div>
        </template>
      </Column>
    </DataTable>

    <Dialog header="Edit Data Siswa" :visible="isModalVisible" @update:visible="isModalVisible = $event" :style="{ width: '80vw' }" :closable="true">
      <div v-if="isEditMode" class="p-fluid grid">
        <div class="col-12 md:col-6" v-for="field in inputFields" :key="field.id">
          <label :for="field.id">{{ field.label }}</label>

          <Dropdown
            v-if="field.type === 'select'"
            v-model="selectedSiswa.kelas_id"
            :options="kelasList"
            optionLabel="nama_kelas"
            optionValue="id"
            placeholder="Pilih Kelas"
            class="w-full"
          />

          <InputText
            v-else
            v-model="selectedSiswa[field.model]"
            :type="field.type"
            :id="field.id"
            class="w-full"
          />
        </div>

        <div class="col-12 mt-3">
          <Button label="Simpan" @click="updateSiswa" class="p-button-success w-full" />
        </div>
      </div>

      <div v-else>
        <DataTable :value="[selectedSiswa]" responsiveLayout="scroll">
          <Column field="nama_lengkap_siswa" header="Nama Lengkap" />
          <Column field="nama_kelas" header="Kelas" />
          <Column field="nis" header="NIS" />
          <Column field="nisn" header="NISN" />
          <Column field="tempat_lahir" header="Tempat Lahir" />
          <Column field="tanggal_lahir" header="Tanggal Lahir" />
          <Column field="agama" header="Agama" />
          <Column field="anak_ke" header="Anak Ke" />
          <Column field="alamat" header="Alamat" />
          <Column field="rt_rw" header="RT/RW" />
          <Column field="dusun" header="Dusun" />
          <Column field="kelurahan" header="Kelurahan" />
          <Column field="kecamatan" header="Kecamatan" />
          <Column field="kode_pos" header="Kode Pos" />
          <Column field="nama_ayah" header="Nama Ayah" />
          <Column field="pekerjaan_ayah" header="Pekerjaan Ayah" />
          <Column field="pendidikan_ayah" header="Pendidikan Ayah" />
          <Column field="nama_ibu" header="Nama Ibu" />
          <Column field="pekerjaan_ibu" header="Pekerjaan Ibu" />
          <Column field="pendidikan_ibu" header="Pendidikan Ibu" />
          <Column field="nomor_telepon" header="Telepon" />
          <Column field="email" header="Email" />
        </DataTable>
      </div>
    </Dialog>
  </div>
</template>

<script>
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';

export default {
  components: { DataTable, Column, Button, Dialog, InputText, Dropdown },
  data() {
    return {
      siswaList: [],
      kelasList: [],
      isModalVisible: false,
      selectedSiswa: {},
      isEditMode: false,
      searchQuery: '',
      inputFields: [
        { id: 'nama_lengkap_siswa', label: 'Nama Lengkap', model: 'nama_lengkap_siswa', type: 'text' },
        { id: 'kelas', label: 'Kelas', model: 'kelas_id', type: 'select' },
        { id: 'nis', label: 'NIS', model: 'nis', type: 'text' },
        { id: 'nisn', label: 'NISN', model: 'nisn', type: 'text' },
        { id: 'tempat_lahir', label: 'Tempat Lahir', model: 'tempat_lahir', type: 'text' },
        { id: 'tanggal_lahir', label: 'Tanggal Lahir', model: 'tanggal_lahir', type: 'date' },
        { id: 'agama', label: 'Agama', model: 'agama', type: 'text' },
        { id: 'anak_ke', label: 'Anak Ke', model: 'anak_ke', type: 'text' },
        { id: 'alamat', label: 'Alamat', model: 'alamat', type: 'text' },
        { id: 'rt_rw', label: 'RT/RW', model: 'rt_rw', type: 'text' },
        { id: 'dusun', label: 'Dusun', model: 'dusun', type: 'text' },
        { id: 'kelurahan', label: 'Kelurahan', model: 'kelurahan', type: 'text' },
        { id: 'kecamatan', label: 'Kecamatan', model: 'kecamatan', type: 'text' },
        { id: 'kode_pos', label: 'Kode Pos', model: 'kode_pos', type: 'text' },
        { id: 'nama_ayah', label: 'Nama Ayah', model: 'nama_ayah', type: 'text' },
        { id: 'pekerjaan_ayah', label: 'Pekerjaan Ayah', model: 'pekerjaan_ayah', type: 'text' },
        { id: 'pendidikan_ayah', label: 'Pendidikan Ayah', model: 'pendidikan_ayah', type: 'text' },
        { id: 'nama_ibu', label: 'Nama Ibu', model: 'nama_ibu', type: 'text' },
        { id: 'pekerjaan_ibu', label: 'Pekerjaan Ibu', model: 'pekerjaan_ibu', type: 'text' },
        { id: 'pendidikan_ibu', label: 'Pendidikan Ibu', model: 'pendidikan_ibu', type: 'text' },
        { id: 'nomor_telepon', label: 'Nomor Telepon', model: 'nomor_telepon', type: 'text' },
        { id: 'email', label: 'Email', model: 'email', type: 'text' },
      ]
    };
  },
  computed: {
    filteredSiswaList() {
      if (!this.searchQuery) return this.siswaList;
      return this.siswaList.filter((siswa) =>
        siswa.nama_lengkap_siswa.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        siswa.nis.toString().includes(this.searchQuery) ||
        siswa.nisn.toString().includes(this.searchQuery)
      );
    },
  },
  methods: {
    fetchKelas() {
      return axios.get('http://127.0.0.1:8000/api/kelas').then((res) => {
        this.kelasList = res.data;
      });
    },
    fetchSiswa() {
      axios.get('http://127.0.0.1:8000/api/siswa').then((res) => {
        const siswaData = res.data;
        this.siswaList = siswaData
          .filter(s => s.id != null)
          .map(s => {
            const kelas = this.kelasList.find(k => k.id === s.kelas_id);
            return {
              ...s,
              nama_kelas: kelas ? kelas.nama_kelas : '-'
            };
          })
          .sort((a, b) => {
            if (a.nama_kelas && b.nama_kelas) {
              return a.nama_kelas.localeCompare(b.nama_kelas, 'id', { numeric: true });
            }
            return 0;
          });
      });
    },
    viewSiswa(id) {
      const siswa = this.siswaList.find(s => s.id === id);
      if (siswa) {
        this.selectedSiswa = { ...siswa };
        this.isModalVisible = true;
        this.isEditMode = false;
      }
    },
    editSiswa(id) {
      const siswa = this.siswaList.find(s => s.id === id);
      if (siswa) {
        this.selectedSiswa = { ...siswa };
        this.isModalVisible = true;
        this.isEditMode = true;
      }
    },
    updateSiswa() {
      axios.put(`http://127.0.0.1:8000/api/siswa/${this.selectedSiswa.id}`, this.selectedSiswa).then(() => {
        this.fetchSiswa();
        this.isModalVisible = false;
      });
    },
    deleteSiswa(id) {
      axios.delete(`http://127.0.0.1:8000/api/siswa/${id}`).then(() => {
        this.fetchSiswa();
      });
    }
  },
  mounted() {
    this.fetchKelas().then(() => this.fetchSiswa());
  }
};
</script>

<style scoped>
.siswa-container {
  padding: 20px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}
h2 {
  margin-bottom: 20px;
  font-size: 24px;
  color: #333;
}
.action-buttons {
  display: flex;
  justify-content: flex-end;
  gap: 5px;
}
.form-group {
  margin-bottom: 15px;
}
input.form-control {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
}
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}
@media (max-width: 768px) {
  .p-datatable {
    font-size: 12px;
  }
}
</style>
