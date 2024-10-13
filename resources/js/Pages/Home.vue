<script setup>
import DropFile from '../Components/custom/DropFile.vue';
import { onMounted, ref, watch, watchEffect} from 'vue';
import { Head } from '@inertiajs/vue3';
import { usePrimeVue } from 'primevue/config';

const files = ref([]);
const edit = ref(false);
const idItem = ref('');
const create = ref(false);

async function getFiles() {
    await axios.post('/home/get-files')
    .then(async res => {
        files.value = res.data;
    })
    .catch(e => {
        console.error(e);
    })

}

onMounted(async () => {
    await getFiles();
});

async function editFile(item){

    idItem.value = item.id;
    edit.value = true;
   
}

async function backToIndex(){
    create.value = false;
    edit.value = false;
}

async function newFile(){
    create.value = true;
}

</script>

<template>

    <div class="flex flex-col w-full h-full">

        <div class="flex flex-row w-full bg-blue-900 shadow-md drop-shadow-md h-[80px]">
            
            <div class="flex justify-end w-full p-5">
                
                <button @click="backToIndex()" class="p-2 bg-black border border-white rounded-md pi pi-arrow-left ">
                    Voltar
                </button>
                
                <button @click="newFile()" class="p-2 bg-black border border-white rounded-md pi pi-plus ">
                    Novo Arquivo
                </button>
            </div>

        </div>

        <div v-if="!create && !edit" class="pt-8">

            <div class="grid grid-cols-4 ">
    
                <div v-for="file in files" class="flex items-center justify-center w-full">

                    <div @click="editFile(file)" class="flex flex-col justify-center bg-red-600  hover:bg-red-500 transition-all opacity-70 w-[220px] p-2 border-white border-2 shadoor-md drop-shadow-md ">

                        <div class="font-bold ">
                            <div>{{ file.id }} - {{ file.file_name }}</div>
                        </div>

                        <div class="pt-1">
                            <div class="w-full border border-white">
                            </div>
                        </div>




                    </div>

                </div>
    
            </div>
        </div>

        <div v-if="edit" class="flex items-center justify-center pt-10 h-100">
            <DropFile :id="idItem"></DropFile>
        </div>
        
        <div v-if="create" class="flex items-center justify-center pt-10 h-100">
            <DropFile :id="null"></DropFile>
        </div>
        
    </div>

</template>

<style>



</style>