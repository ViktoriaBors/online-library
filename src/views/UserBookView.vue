<template>
    <common-navbar :to="['/user', '/user/issue', '/user/profile', '/logout']"></common-navbar>

    <form action="" @submit.prevent class="flex flex-col items-center mx-4 mt-4">
        <div class="w-full md:w-1/2">
            <div class="relative flex flex-col items-stretch mb-2 md:flex-row ">
                <base-input :value="search" :class="'relative flex-auto min-w-0 block'" @input="search = $event"
                    @change="search = $event" placeholder="Search" aria-label="Search" />
                <base-button @click="searchBook">
                    Search </base-button>
            </div>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row">
            <base-checkbox :label="'Available'"
                :class="'bg-gray-100 p-5 border-b border-gray-200 sm:border-b-0 sm:border-r'"
                @change="available = $event" />
            <base-checkbox :label="'Highest Rate'"
                :class="'bg-gray-100 p-5 border-b border-gray-200 sm:border-b-0 sm:border-r'" @change="rate = $event" />
        </div>
        <div class="flex flex-col items-center justify-center p-2 m-4 bg-gray-100 rounded">
            <p>Choose a category</p>
            <base-select :options="getCategories" :value="chosenCategory" @change="chosenCategory = $event"></base-select>
        </div>
    </form>

    <div v-if="result.length > 0" class="flex flex-row justify-center m-4">
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
                        <button :data-bookId="data.id" @click="bookRequest"
                            class="block text-sm leading-tight text-teal-800 uppercase md:text-md">
                            {{ data.available === 'Available' ? 'Request' : '-' }}
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
    
<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseInput from "@/components/common/base-input.vue";
import baseButton from "@/components/common/base-button.vue";
import baseCheckbox from "@/components/common/base-checkbox.vue";
import baseSelect from "@/components/common/base-select.vue";
import baseTable from "@/components/common/base-table.vue";
import { ref, reactive, onMounted } from "vue";

const headline = ['Book Id', 'Book Title', 'Author', 'Category', 'Language', 'Available']
let search = ref(undefined);
let chosenCategory = ref(undefined)
let available = ref(false)
let rate = ref(false)
let result = ref([])

let bookRequest = ($event) => {
    console.log($event.target.dataset.bookid)
    return $event.target.dataset.bookid
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

const searchBook = (() => {
    fetch('https://dummyjson.com/products?limit=10', {
        method: 'GET'
    })
        .then(res => res.json())
        .then(data => {
            data.products.forEach(element => {
                let one = {
                    id: element.id,
                    title: element.title,
                    author: element.brand,
                    category: element.category,
                    language: element.brand,
                    available: Math.random() * 2 > 1 ? 'Available' : 'N/A'
                }
                result.value.push(one)
            });
        });
})


</script>
    
<style></style>