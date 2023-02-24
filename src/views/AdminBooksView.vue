// Rename table and variable
<template>
    <common-navbar
        :to="['/admin', '/admin/categories', '/admin/languages', '/admin/authors', '/admin/books', '/admin/users', '/admin/issues', '/logout']"></common-navbar>
    <div class="flex flex-row justify-center m-4">
        <base-table :headline="headline" :data="result">
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
                <tr class="h-8 bg-white border-b md:h-14 dark:bg-gray-900 dark:border-gray-700" v-for="data in result"
                    :key="data.id">
                    <td class="px-6">
                        <button :data-categoryId="data.id" @click="disable"
                            class="block text-sm leading-tight text-teal-800 uppercase md:text-md">
                            {{ data.status === 'Enabled' ? 'Disabled' : 'Enabled' }}
                        </button>
                        <button :data-categoryId="data.id" @click="edit"
                            class="block text-sm leading-tight text-teal-800 uppercase md:text-md">
                            Edit
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="max-w-md mx-2 mb-4 sm:mx-auto">
        <p>Change Book details</p>
        <form action="" @submit.prevent v-if="!changeCategoryId" class="max-w-72">
            <div class="grid gap-2 mb-6 md:grid-cols-2">
                <div>
                    <p class="mt-2 ml-1">Book Title</p>
                    <base-input :value="firstName" :class="'w-full'" @input="firstName = $event"
                        @change="firstName = $event" placeholder="First Name" aria-label="first name" required>
                    </base-input>
                </div>
                <div>
                    <p class="mt-2 ml-1">No. of copies</p>
                    <base-input :value="lastName" :class="'w-full'" :type="'number'" @input="lastName = $event"
                        @change="lastName = $event" placeholder="Last Name" aria-label="last name" required>
                    </base-input>
                </div>
            </div>
            <div class="grid gap-2 mb-6 md:grid-cols-2">
                <div>
                    <p>Choose a category</p>
                    <base-select :options="getCategories" :value="chosenCategory"
                        @change="chosenCategory = $event"></base-select>
                </div>
                <div>
                    <p>Choose a category</p>
                    <base-select :options="getCategories" :value="chosenCategory"
                        @change="chosenCategory = $event"></base-select>
                </div>
                <div>
                    <p>Choose a category</p>
                    <base-select :options="getCategories" :value="chosenCategory"
                        @change="chosenCategory = $event"></base-select>
                </div>
            </div>
            <base-button @click="saveCategory(changeCategoryId)">
                {{ changeCategoryId === undefined ? 'Add New' : 'Save changes' }}
            </base-button>
        </form>
        <form action="" @submit.prevent v-if="changeCategoryId" class="max-w-72">
            <div class="grid gap-2 mb-6 md:grid-cols-2">
                <div>
                    <p class="mt-2 ml-1">Book Title</p>
                    <base-input :value="firstName" :class="'w-full'" @input="firstName = $event"
                        @change="firstName = $event" placeholder="First Name" aria-label="first name" required>
                    </base-input>
                </div>
                <div>
                    <p class="mt-2 ml-1">No. of copies</p>
                    <base-input :value="lastName" :class="'w-full'" :type="'number'" @input="lastName = $event"
                        @change="lastName = $event" placeholder="Last Name" aria-label="last name" required>
                    </base-input>
                </div>
            </div>
            <div class="grid gap-2 mb-6 md:grid-cols-2">
                <div>
                    <p>Choose a category</p>
                    <base-select :options="getCategories" :value="chosenCategory"
                        @change="chosenCategory = $event"></base-select>
                </div>
                <div>
                    <p>Choose a category</p>
                    <base-select :options="getCategories" :value="chosenCategory"
                        @change="chosenCategory = $event"></base-select>
                </div>
                <div>
                    <p>Choose a category</p>
                    <base-select :options="getCategories" :value="chosenCategory"
                        @change="chosenCategory = $event"></base-select>
                </div>
            </div>
            <base-button @click="saveCategory(changeCategoryId)">
                {{ changeCategoryId === undefined ? 'Add New' : 'Save changes' }}
            </base-button>
        </form>
    </div>
</template>
    
<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseInput from "@/components/common/base-input.vue";
import baseTable from "@/components/common/base-table.vue";
import baseButton from "@/components/common/base-button.vue";
import baseSelect from "@/components/common/base-select.vue";
import { ref, onMounted } from 'vue';

const headline = ['Category Id', 'Name', 'Status', 'Created', 'Updated']

const result = ref([]);
const changeCategoryId = ref(undefined)
const changeCategoryName = ref(undefined)
const newCategoryName = ref(undefined)
let chosenCategory = ref(undefined)

const edit = ($event) => {
    console.log($event.target.dataset.categoryid)
    changeCategoryId.value = $event.target.dataset.categoryid
    changeCategoryName.value = result.value.find(element => element.id == changeCategoryId.value)
}

const saveCategory = (id) => {
    console.log(id);
    if (id === undefined) {
        console.log("save new category", newCategoryName.value)
        newCategoryName.value = undefined
    } else {
        console.log("save changed category name", changeCategoryName.value)
        changeCategoryName.value = undefined
        changeCategoryId.value = undefined
    }
}

const getCategories = [
    { value: '', label: 'No preferred category' },
    { value: 'board', label: 'Board books' },
    { value: 'picture', label: 'Picture books' },
    { value: 'easyReader', label: 'Easy readers' },
    { value: 'chapter', label: 'Chapter books' },
    { value: 'juvenile', label: 'Juvenile books' },
    { value: 'middleGrade', label: 'Middle Grade' },
    { value: 'youngAdult', label: 'Young Adult' }
]

onMounted(() => {
    fetch('https://dummyjson.com/products?limit=10', {
        method: 'GET'
    })
        .then(res => res.json())
        .then(data => {
            data.products.forEach(element => {
                let one = {
                    id: element.id,
                    title: element.title,
                    status: Math.random() * 2 > 1 ? 'Enabled' : 'Disabled',
                    created: new Date(Date.now()).toISOString().slice(0, 10),
                    updated: new Date(Date.now()).toISOString().slice(0, 10),
                }
                result.value.push(one)
            });
        });
})
</script>
    
<style></style>