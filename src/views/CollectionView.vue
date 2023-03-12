<template>
  <div class="collection">
    <common-navbar />
    <form
      action=""
      @submit.prevent
      class="flex flex-col items-center mx-4 mt-4"
    >
      <div class="w-full md:w-1/2">
        <div class="relative flex flex-col items-stretch mb-2 md:flex-row">
          <base-input
            :value="search"
            :class="'relative flex-auto min-w-0 block'"
            @input="search = $event"
            @change="search = $event"
            placeholder="Search"
            aria-label="Search"
          />
          <base-button @click="searchBook"> Search </base-button>
        </div>
      </div>
      <div class="flex flex-col gap-2 sm:flex-row"></div>
      <div
        class="flex flex-col items-center justify-center gap-2 p-2 m-4 bg-gray-100 rounded sm:flex-row"
      >
        <div>
          <p>Choose a category</p>
          <base-select
            :options="categories"
            :value="chosenCategory"
            @change="chosenCategory = $event"
          ></base-select>
        </div>
        <div>
          <p>Choose a language</p>
          <base-select
            :options="languages"
            :value="chosenLanguage"
            @change="chosenLanguage = $event"
          ></base-select>
        </div>
      </div>
    </form>
  </div>
  <div class="flex justify-center">
    <div
      v-if="result.length > 0"
      class="grid grid-cols-1 gap-2 md:grid-cols-3 md:mx-2"
    >
      <div v-for="card in result" :key="card.id">
        <book-card :data="card"></book-card>
      </div>
    </div>
    <div v-if="noResult">
      <p>No books found with this search options</p>
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
let chosenCategory = ref(undefined);
let chosenLanguage = ref(undefined);
let result = ref([]);
let noResult = ref(false);
let categories = ref([]);
let languages = ref([]);

onMounted(() => {
  fetch("http://localhost/api/categories")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      categories.value = data.map((category) => {
        return { value: category.categoryId, label: category.categoryName };
      });
      categories.value.unshift({ value: "undefined", label: "-" });
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
  fetch("http://localhost/api/languages")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      languages.value = data.map((language) => {
        return { value: language.langId, label: language.language };
      });
      languages.value.unshift({ value: "undefined", label: "-" });
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
});

const searchBook = () => {
  fetch(
    "http://localhost/api/collection?category=" +
      chosenCategory.value +
      "&language=" +
      chosenLanguage.value +
      "&search=" +
      search.value,
    {}
  )
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      if (data.length === 0) {
        noResult.value = true;
        result.value = data;
      } else {
        result.value = data;
        noResult.value = false;
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};
</script>

<style></style>
