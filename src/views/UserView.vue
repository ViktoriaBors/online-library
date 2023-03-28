<template>
  <common-navbar
    :to="['/user', '/user/issue', '/user/profile', '/logout']"
  ></common-navbar>

  <h1
    v-if="historyDetail.length == 0"
    class="block m-4 text-2xl font-bold leading-tight text-center text-teal-800 uppercase"
  >
    No current book loans
  </h1>
  <div class="mx-4" v-if="historyDetail.length > 0">
    <h1
      class="block m-4 text-2xl font-bold leading-tight text-teal-800 uppercase"
    >
      Your History
    </h1>
    <div class="flex flex-row justify-center m-4">
      <base-table :headline="headline" :data="historyDetail"> </base-table>
      <table class="text-sm text-left text-gray-800 dark:text-gray-400">
        <thead
          class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
        >
          <tr>
            <th scope="col" class="px-6 py-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            class="bg-white border-b dark:bg-gray-900 dark:border-gray-700"
            v-for="data in historyDetail"
            :key="data.id"
          >
            <td class="px-6 py-4">
              <button
                :data-issueId="data.issueId"
                @click="bookReturn"
                class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
              >
                Return
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <p class="mt-2 text-2xl font-bold leading-tight text-center text-teal-800">
      {{ resultMessage }}
    </p>
  </div>
</template>

<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseButton from "@/components/common/base-button.vue";
import baseTable from "@/components/common/base-table.vue";
import { ref, onMounted } from "vue";

const headline = [
  "Issue Id",
  "Book Title",
  "Author",
  "Category",
  "Language",
  "Return Date",
];
let historyDetail = ref([]);
let resultMessage = ref(undefined);

onMounted(() => {
  const user = localStorage.getItem("user");
  const userParse = JSON.parse(user);
  const userId = userParse.sub;
  fetch("http://localhost/api/user/userHistory?userId=" + userId, {})
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      historyDetail.value = data;
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
});

let bookReturn = ($event) => {
  resultMessage.value = undefined;
  console.log($event.target.dataset.issueid);
  resultMessage.value = undefined;
  const issueId = $event.target.dataset.issueid;
  const body = {
    issueId: Number(issueId),
  };
  fetch("http://localhost/api/user/returnBook", {
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
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};
</script>

<style></style>