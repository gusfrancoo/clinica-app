<script setup>

import { onMounted, ref, watch, watchEffect} from 'vue';
import { Head } from '@inertiajs/vue3';
import { usePrimeVue } from 'primevue/config';

const $primevue = usePrimeVue();

const props = defineProps({
    id: String
})

const isDragOver = ref(false);
const message = ref('Arraste e solte um arquivo aqui ou clique para selecionar.');
const previewUrl = ref(null);
const isImage = ref(false);
const fileInfo = ref({ name: '', type: '', size: 0 });
const files = ref([]);
const fileInput = ref(null);
const progressValue = ref(0);


async function getFiles(id){

    let  param = {
        id: id
    }

    if (id != null) {

        await axios.post('/home/get-files', param)
        .then(async res => {
            const fileData = res.data;
            await loadFilePreview(fileData);
        })
        .catch(e => {
            console.error(e);
        })
    }
}

onMounted(async () => {
    await getFiles(props.id);
});


const triggerFileInput = () => {
  fileInput.value.click();
};

const onDragOver = () => {
  isDragOver.value = true;
};

const onDragLeave = () => {
  isDragOver.value = false;
};

const onDrop = async (event) => {
  isDragOver.value = false;
  files.value = event.dataTransfer.files;
  if (files.value.length > 0) {
    await handleFile(files.value[0]);
  }
};

const onFileSelect = async (event) => {
  const files = event.target.files;
  if (files.length > 0) {
    await handleFile(files[0]);
  }
};

const handleFile = async (file) => {
  previewUrl.value = URL.createObjectURL(file);
  isImage.value = file.type.startsWith('image/');
  fileInfo.value = { name: file.name, type: file.type, size: file.size };
  progressValue.value = 100;
};

const uploadFile = async () => {

    const formData = new FormData();
    formData.append('file', files.value[0]);
    try {
        
        await axios.post('/home/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },

        })
        .then(res => {
            console.log(res.data);
        })

    } catch (error) {
        console.error(error);
    }
}

const loadFilePreview = (fileData) => {

  previewUrl.value = `${window.location.origin}${fileData[0].file_link}`;
  isImage.value = fileData[0].type.startsWith('image/');
  fileInfo.value = {
    name: fileData[0].file_name,
    type: fileData[0].type,
    size: fileData[0].size,
  };
  progressValue.value = 100;

};


const removeFile = () => {
  previewUrl.value = null;
  isImage.value = false;
  fileInfo.value = { name: '', type: '', size: 0 };
  fileInput.value.value = null;
  progressValue.value = 0;
  message.value = 'Arraste e solte um arquivo aqui ou clique para selecionar.';
};

const formatSize = (bytes) => {
    const k = 1024;
    const dm = 3;
    const sizes = $primevue.config.locale.fileSizeTypes;

    if (bytes === 0) {
        return `0 ${sizes[0]}`;
    }

    const i = Math.floor(Math.log(bytes) / Math.log(k));
    const formattedSize = parseFloat((bytes / Math.pow(k, i)).toFixed(dm));

    return `${formattedSize} ${sizes[i]}`;
};


</script>
<template>

    <div class="flex flex-col w-[60%] ">

        


        <div class="flex flex-col border items-center justify-center rounded-md  border-white p-2 h-64" 
                @dragover.prevent="onDragOver" @dragleave="onDragLeave" @drop.prevent="onDrop"
                :class="{ 'drag-over': isDragOver }">


            <div class="flex flex-col items-start justify-start w-full h-full space-y-2">

                <div class="flex justify-center items-center rounded-full border border-white">
                    <button class="p-2 !text-md drop-shadow-md rounded-full shadow-md pi pi-file hover:bg-gray-400 hover:text-white transition-all hover:opacity-60" @click="triggerFileInput"></button>
                </div>

                <div class="w-full">
                    <ProgressBar  :value="progressValue"></ProgressBar>
                </div>


            </div>    


            <div class="flex flex-row space-x-3">
                
                <div class="bg-white">
                    <input
                        type="file"
                        ref="fileInput"
                        @change="onFileSelect"
                        style="display: none"/>
                </div>


                <div v-if="previewUrl" class="flex flex-col w-full">

                    <div v-if="isImage">
                        <img  :src="previewUrl" class="w-52" alt="Pré-visualização do arquivo" />
                    </div>

                    <div v-else>
                        <p>Nome: {{ fileInfo.name }}</p>
                        <p>Tipo: {{ fileInfo.type }}</p>
                        <p>Tamanho: {{ fileInfo.size }} bytes</p>
                    </div>

                    <div class="flex flex-row justify-center items-center w-full space-x-3">

                        <div class="pt-4 flex  justify-center " >
                            <i @click="uploadFile()" class="p-3  pi pi-check text-color-custom rounded-full bg-white opacity-80 text-xl font-bold text-green-500 border-[3px] border-green-700" ></i>
                        </div>
                        <div class="pt-4 flex  justify-center" >
                            <i @click="removeFile()" class="p-3  pi pi-times text-color-custom rounded-full bg-white opacity-80 text-xl font-bold text-red-700 border-[3px] border-red-700" ></i>
                        </div>

                    </div>

    

                </div>    

                <div v-else class="flex flex-col w-full text-nowrap justify-center items-center space-y-3 mb-3">

                    <div class="flex justify-center items-center border border-white rounded-full p-4">
                        <i class="pi pi-cloud-upload !text-[35px]"></i>
                    </div>

                    <div>
                        <p >{{ message }}</p>
                    </div>
                </div>

            </div>    


        </div>


    </div>


</template>
