// Rename table and variable
<template>
    <common-navbar
    :to="['/admin','/admin/categories', '/admin/languages','/admin/authors','/admin/books','/admin/users','/admin/issues', '/logout']"
    ></common-navbar>
    <div     
    class="flex flex-row justify-center m-4">
    <base-table
    :headline="headline"
    :data="result">
    </base-table>
    <table class="text-sm text-left text-gray-800 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase lg:h-14 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                      Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="h-8 bg-white border-b md:h-14 dark:bg-gray-900 dark:border-gray-700"
                    v-for="data in result"
                    :key="data.id"
                >
                    <td class="px-6">
                        <button
                        :data-categoryId ="data.id"
                        @click="disable"
                        class="block text-sm leading-tight text-teal-800 uppercase md:text-md">
                      {{ data.status === 'Enabled' ? 'Disabled' : 'Enabled' }}
                      </button>
                      <button
                        :data-categoryId ="data.id"
                        @click="edit"
                        class="block text-sm leading-tight text-teal-800 uppercase md:text-md">
                        Edit
                      </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="max-w-md mx-2 mb-4 sm:mx-auto">
                <form class="" @submit.prevent>
                    <p>Category Name</p>
                    <div class="flex flex-col md:flex-row">
                    <base-input placeholder=" " required 
                    v-if="changeCategoryId"
                    :value="changeCategoryName.title"
                    @input="changeCategoryName.title = $event"
                    @change = "changeCategoryName.title = $event"
                        />
                    <base-input placeholder=" " required 
                    v-if="!changeCategoryId"
                    :value="newCategoryName"
                    :class="'md:flex-1'"
                    @input="newCategoryName = $event"
                    @change = "newCategoryName = $event"
                    />
                    <base-button 
                    @click="saveCategory(changeCategoryId)"
                    >
                    {{ changeCategoryId === undefined ? 'Add New' : 'Save changes' }}
                    </base-button>
                    </div>
                </form>
            </div>
    </template>
    
    <script setup>
    import commonNavbar from "@/components/common/common-navbar.vue";
    import baseInput from "@/components/common/base-input.vue";
    import baseTable from "@/components/common/base-table.vue";
    import baseButton from "@/components/common/base-button.vue";
    import { ref, onMounted } from 'vue';
  
const headline = ['Category Id','Name', 'Status', 'Created', 'Updated']

const result = ref([]);
const changeCategoryId = ref(undefined)
const changeCategoryName = ref(undefined)
const newCategoryName = ref(undefined)

const edit=($event)=>{
    console.log($event.target.dataset.categoryid)
    changeCategoryId.value = $event.target.dataset.categoryid
    changeCategoryName.value =  result.value.find(element => element.id == changeCategoryId.value)
}    

const saveCategory = (id) => {
    console.log(id);
    if(id === undefined){
        console.log("save new category", newCategoryName.value)
        newCategoryName.value = undefined
    } else {
        console.log("save changed category name", changeCategoryName.value)
        changeCategoryName.value = undefined
        changeCategoryId.value = undefined
    }
}

onMounted(() => {
  fetch('https://dummyjson.com/products?limit=10', {
  method: 'GET'
})
.then(res => res.json())
.then(data => {
    data.products.forEach(element => {
        let one = {
            id:element.id,
            title: element.title,
            status:Math.random()*2 > 1 ? 'Enabled' : 'Disabled',
            created: new Date(Date.now()).toISOString().slice(0, 10),
            updated: new Date(Date.now()).toISOString().slice(0, 10),
        }
        result.value.push(one) 
    });
});
})
    </script>
    
    <style>
    </style>