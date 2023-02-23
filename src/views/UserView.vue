<template>
<common-navbar
:to="['/user','/user/issue', '/user/profile', '/logout']"
></common-navbar>

<div class="mx-4"
v-if="historyDetail.length > 0">
<h1  class="block m-4 text-2xl font-bold leading-tight text-teal-800 uppercase">Your History</h1>
<base-table
:headline="headline"
:data="historyDetail"
:actionLabel="'Return'"
:showActionLabel="true"
@click="bookReturn">
</base-table>
</div>
</template>

<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseButton from "@/components/common/base-button.vue";
import baseTable from "@/components/common/base-table.vue";
import { ref, onMounted } from "vue";

const headline = ['Book Id','Book Title', 'Author', 'Category', 'Language', 'Actions']
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