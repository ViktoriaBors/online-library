<template>
  <common-navbar
    :to="['/user', '/user/issue', '/user/profile', '/logout']"
  ></common-navbar>

  <form action="" @submit.prevent class="flex flex-col items-center mx-4 mt-4">
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
  <div v-if="noResult">
    <p>No books found with this search options</p>
  </div>

  <div v-if="result.length > 0" class="flex flex-row justify-center m-4">
    <base-table :headline="headline" :data="result"> </base-table>
    <table class="text-sm text-left text-gray-800 dark:text-gray-400">
      <thead
        class="text-xs text-gray-700 uppercase lg:h-14 bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
      >
        <tr>
          <th scope="col" class="px-6 py-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr
          class="h-8 bg-white border-b md:h-14 dark:bg-gray-900 dark:border-gray-700"
          v-for="data in result"
          :key="data.id"
        >
          <td class="px-6">
            <button
              v-if="!isBanned"
              :data-bookId="data.id"
              @click="bookRequest"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              {{ data.availability === "Available" ? "Request" : "-" }}
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <p class="mt-2 text-2xl font-bold leading-tight text-center text-teal-800">
    {{ resultMessage }}
  </p>
</template>
    
<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseInput from "@/components/common/base-input.vue";
import baseButton from "@/components/common/base-button.vue";
import baseCheckbox from "@/components/common/base-checkbox.vue";
import baseSelect from "@/components/common/base-select.vue";
import baseTable from "@/components/common/base-table.vue";
import { ref, reactive, onMounted } from "vue";
import jwt_decode from "jwt-decode";

const headline = [
  "Book Id",
  "Book Title",
  "Author",
  "Category",
  "Language",
  "Available",
];
let search = ref(undefined);
let chosenCategory = ref(undefined);
let chosenLanguage = ref(undefined);
let result = ref([]);
let noResult = ref(false);
let categories = ref([]);
let languages = ref([]);
let resultMessage = ref(undefined);
let isBanned = ref(undefined);

onMounted(() => {
  const user = localStorage.getItem("user");
  const userParse = JSON.parse(user);
  const userId = userParse.sub;
  fetch("http://localhost/api/getUser/" + userId, {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      isBanned.value = data.isBanned == 0 ? false : true;
      if (isBanned.value) {
        resultMessage.value =
          "You cannot request books, because you are banned.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
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
        result.value = data.map((element) => {
          return {
            id: element.bookId,
            title: element.title,
            author: element.authorName,
            language: element.language,
            category: element.categoryName,
            availability:
              element.availability > 0 ? "Available" : "Not available",
          };
        });
        noResult.value = false;
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

let bookRequest = ($event) => {
  resultMessage.value = undefined;
  const user = localStorage.getItem("user");
  const userParse = JSON.parse(user);
  const userId = userParse.sub;
  const bookId = $event.target.dataset.bookid;
  const body = {
    userId: userId,
    bookId: Number(bookId),
  };
  fetch("http://localhost/api/user/issueBook", {
    method: "POST",
    cors: "no-cors",
    // credentials:"include",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(body),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      resultMessage.value = data.message;
      result.value = [];
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};
</script>
    
<style></style>