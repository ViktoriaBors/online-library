<template>
  <common-navbar
    :to="[
      '/admin',
      '/admin/categories',
      '/admin/languages',
      '/admin/authors',
      '/admin/books',
      '/admin/users',
      '/admin/issues',
      '/logout',
    ]"
  ></common-navbar>
  <div class="flex flex-row justify-center m-4" v-if="!resultMessage">
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
          :key="data.issueId"
        >
          <td class="px-6">
            <button
              v-if="data.lateFine == 0"
              :data-issueId="data.issueId"
              @click="returnBook(data.issueId)"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              Return
            </button>
            <button
              v-if="data.lateFine > 0"
              :data-issueId="data.issueId"
              @click="paidFine(data.issueId)"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              Paid
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="flex flex-col items-center justify-center">
    <p
      class="mt-2 text-2xl font-bold leading-tight"
      :class="resultMessage ? 'text-teal-800' : 'text-red-900'"
    >
      {{ resultMessage ? resultMessage : errorMessage }}
    </p>
  </div>
  <div v-if="resultMessage" class="flex justify-center">
    <base-button @click="refreshPage"> Refresh </base-button>
  </div>
</template>
    
<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseInput from "@/components/common/base-input.vue";
import baseTable from "@/components/common/base-table.vue";
import baseButton from "@/components/common/base-button.vue";
import baseSelect from "@/components/common/base-select.vue";
import { ref, onMounted } from "vue";

const headline = [
  "Issue Id",
  "Book Title",
  "Author",
  "User Name",
  "User Email",
  "Status",
  "Late Fine",
  "Latest return",
];

const result = ref([]);
let resultMessage = ref(undefined);
let errorMessage = ref(undefined);

let navOptions = ref([
  "/admin",
  "/admin/categories",
  "/admin/languages",
  "/admin/authors",
  "/admin/books",
  "/admin/issues",
  "/logout",
]);

const admin = localStorage.getItem("admin");
const adminParse = JSON.parse(admin);
if (adminParse.role == "sudo") {
  navOptions.value = [
    "/admin",
    "/admin/categories",
    "/admin/languages",
    "/admin/authors",
    "/admin/books",
    "/admin/users",
    "/admin/issues",
    "/logout",
  ];
}

const returnBook = (id) => {
  const issueId = Number(id);
  const title = result.value.find((issue) => {
    return issue.issueId == issueId;
  }).title;
  const body = {
    issueId,
    title,
  };
  fetch("http://localhost/api/admin/returnBook", {
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
      if (data.result === "successful") {
        resultMessage.value =
          "Book with issue id - " + issueId + " has been returned.";
      } else {
        resultMessage.value = "Book return status update failed.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const paidFine = (id) => {
  const issueId = Number(id);
  const finePaid = true;
  const body = {
    issueId,
    finePaid,
  };
  fetch("http://localhost/api/admin/returnBook", {
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
      if (data.result === "successful") {
        resultMessage.value =
          "Late fine for Book with issue id - " + issueId + " has been paid.";
      } else {
        resultMessage.value = "Status update failed.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

onMounted(() => {
  fetch("http://localhost/api/admin/issuedbooks", {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      result.value = data.map((obj) => {
        return {
          issueId: obj.issueId,
          title: obj.title,
          author: obj.author,
          user: obj.user,
          email: obj.email,
          isReturned: obj.isReturned === "1" ? "Returned" : "Issued",
          lateFine: obj.lateFine === null ? 0 : obj.lateFine,
          latestReturn: obj.returnDate,
        };
      });
      console.log(result.value);
      if (data.length == 0) {
        resultMessage.value = "No issued books.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
});

const refreshPage = () => {
  resultMessage.value = undefined;
  fetch("http://localhost/api/admin/issuedbooks", {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      result.value = data.map((obj) => {
        return {
          issueId: obj.issueId,
          title: obj.title,
          author: obj.author,
          user: obj.user,
          email: obj.email,
          isReturned: obj.isReturned === "1" ? "Returned" : "Issued",
          lateFine: obj.lateFine === null ? 0 : obj.lateFine,
          latestReturn: obj.returnDate,
        };
      });
      console.log(result.value);
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};
</script>
    
<style>
</style>