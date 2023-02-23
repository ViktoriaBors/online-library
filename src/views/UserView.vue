<template>
<common-navbar
:to="['/user','/user/issue', '/user/profile', '/logout']"
></common-navbar>

<h1  
v-if="historyDetail.length == 0"
class="block m-4 text-2xl font-bold leading-tight text-teal-800 uppercase">No History</h1>
<div class="mx-4"
v-if="historyDetail.length > 0">
<h1  class="block m-4 text-2xl font-bold leading-tight text-teal-800 uppercase">Your History</h1>
<div  class="flex flex-row justify-center m-4">
<base-table
:headline="headline"
:data="historyDetail">
</base-table>
<table class="text-sm text-left text-gray-800 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                      Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700"
                    v-for="data in historyDetail"
                    :key="data.id"
                >
                    <td class="px-6 py-4">
                        <button
                        :data-bookId ="data.id"
                        @click="bookReturn"
                        class="block text-sm leading-tight text-teal-800 uppercase md:text-md">
                      Return
                      </button>
                    </td>
                </tr>
            </tbody>
        </table>
</div>
</div>
</template>

<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseButton from "@/components/common/base-button.vue";
import baseTable from "@/components/common/base-table.vue";
import { ref, onMounted } from "vue";

const headline = ['Book Id','Book Title', 'Author', 'Category', 'Language']
let historyDetail = ref([])

onMounted(() => {
  fetch('https://dummyjson.com/products?limit=2', {
  method: 'GET'
})
.then(res => res.json())
.then(data => {
    data.products.forEach(element => {
        let one = {
            id:element.id,
            title: element.title,
            author:element.brand,
            category: element.category,
            language: element.brand,
        }
        historyDetail.value.push(one) 
    });
});
})

let bookReturn = ($event)=>{
    console.log($event.target.dataset.bookid)
    return $event.target.dataset.bookid
}


</script>

<style>
</style>