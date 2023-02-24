<template>
  <div class="collection">
    <common-navbar />
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
        <base-checkbox :label="'Available'" :class="'bg-gray-100 p-5 border-b border-gray-200 sm:border-b-0 sm:border-r'"
          @change="available = $event" />
        <base-checkbox :label="'Highest Rate'"
          :class="'bg-gray-100 p-5 border-b border-gray-200 sm:border-b-0 sm:border-r'" @change="rate = $event" />
      </div>
      <div class="flex flex-col items-center justify-center p-2 m-4 bg-gray-100 rounded">
        <p>Choose a category</p>
        <base-select :options="getCategories" :value="chosenCategory" @change="chosenCategory = $event"></base-select>
      </div>
    </form>
  </div>
  <div class="grid gap-2 grid-cols md:grid-cols-3">
    <div v-if="result.length > 0" v-for="card in result" :key="card.id" class="flex justify-center">
      <book-card :data="card"></book-card>
    </div>
  </div>
</template>

<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseInput from "@/components/common/base-input.vue";
import baseButton from "@/components/common/base-button.vue";
import baseCheckbox from "@/components/common/base-checkbox.vue";
import baseSelect from "@/components/common/base-select.vue";
import bookCard from "@/components/sections/book-card.vue";
import { ref, reactive, onMounted } from "vue";


let search = ref(undefined);
let chosenCategory = ref(undefined)
let available = ref(false)
let rate = ref(false)
let result = ref([])

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
      result.value = data.products
    });
})



</script>

<style></style>
