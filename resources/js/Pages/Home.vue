<script setup>
import DropFile from '../Components/custom/DropFile.vue';
import { onMounted, ref, watch, watchEffect} from 'vue';
import { Head } from '@inertiajs/vue3';
import { usePrimeVue } from 'primevue/config';

const files = ref([]);
const edit = ref(false);
const idItem = ref('');

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


</script>

<template>

    <div class="flex flex-col w-full h-full">

        <div class="bg-blue-900 shadow-md drop-shadow-md h-[80px]">

        </div>


        <div v-if="!edit" class="pt-8">

            <div class="grid grid-cols-4 ">
    
                <div v-for="file in files" class="w-full flex justify-center items-center">

                    <div @click="editFile(file)" class="flex flex-col justify-center bg-red-600  hover:bg-red-500 transition-all opacity-70 w-[220px] p-2 border-white border-2 shadoor-md drop-shadow-md ">

                        <div class=" font-bold">
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

        <div v-if="edit" class="flex justify-center items-center pt-10  h-100">
            <DropFile :id="idItem"></DropFile>
        </div>
        
    </div>

</template>

<style>



</style>