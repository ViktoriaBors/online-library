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
  <div v-if="!resultMessage" class="flex flex-row justify-center m-4">
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
          :key="data.langId"
        >
          <td class="px-6">
            <button
              :data-langId="data.langId"
              @click="changeLanguageStatus(data.langId)"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              {{ data.isActive === "Active" ? "Disable" : "Enable" }}
            </button>
            <button
              :data-langId="data.langId"
              @click="editLanguage(data.langId)"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              Edit
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <p
    class="mt-2 text-2xl font-bold leading-tight"
    :class="resultMessage ? 'text-teal-800' : 'text-red-900'"
  >
    {{ resultMessage ? resultMessage : errorMessage }}
  </p>
  <div class="max-w-md mx-2 mb-4 sm:mx-auto" v-if="!resultMessage">
    <form class="" @submit.prevent>
      <div class="flex flex-col md:flex-row">
        <div v-if="updateLanguage">
          <p>Language</p>
          <base-input
            placeholder=" "
            required
            :value="updateLanguage"
            @input="updateLanguage = $event"
            @change="updateLanguage = $event"
          />
          <base-button @click="changeLanguage()"> Save Changes </base-button>
          <base-button @click="updateLanguage = undefined">
            Cancel
          </base-button>
        </div>
        <div v-if="!updateLanguage">
          <p>Language</p>
          <base-input
            placeholder=" "
            required
            :value="newLanguage"
            :class="'md:flex-1'"
            @input="newLanguage = $event"
            @change="newLanguage = $event"
          />
          <base-button @click="addNewLanguage()">
            Add new language
          </base-button>
        </div>
      </div>
    </form>
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
import { ref, onMounted } from "vue";

const headline = ["Language Id", "Language", "Status"];

const result = ref([]);
let resultMessage = ref(undefined);
let errorMessage = ref(undefined);
let newLanguage = ref(undefined);
let updateLanguage = ref(undefined);
let languageToChangeId = ref(undefined);

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

const changeLanguageStatus = (id) => {
  const langId = Number(id);
  const status = result.value.find((book) => {
    return book.langId == langId;
  });
  const body = {
    langId,
    isActive: status.isActive,
  };
  fetch("http://localhost/api/admin/languageStatus", {
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
        resultMessage.value = "Language status update was successful.";
      } else {
        resultMessage.value = "Language status update failed.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const editLanguage = (id) => {
  languageToChangeId.value = id;
  fetch("http://localhost/api/language/" + id, {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      updateLanguage.value = data.language;
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const changeLanguage = () => {
  if (!updateLanguage.value) {
    errorMessage.value = "Please fill up the missing field!";
    return;
  }
  const langId = result.value.find((language) => {
    return language.langId == languageToChangeId.value;
  }).langId;
  const body = {
    langId,
    language: updateLanguage.value,
  };
  fetch("http://localhost/api/admin/language", {
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
        resultMessage.value = "Language update was successful.";
      } else {
        resultMessage.value = "Language name update failed.";
      }
      languageToChangeId.value = undefined;
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const addNewLanguage = () => {
  if (!newLanguage.value) {
    errorMessage.value = "Please fill up the missing field!";
    return;
  }
  const body = {
    language: newLanguage.value,
  };
  fetch("http://localhost/api/admin/newLanguage", {
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
        resultMessage.value = "New language has been added successfully.";
      } else {
        resultMessage.value = "Failed to add new language.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

onMounted(() => {
  fetch("http://localhost/api/languages", {
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
          langId: obj.langId,
          language: obj.language,
          isActive: obj.isActive === "1" ? "Active" : "Inactive",
        };
      });
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
});

const refreshPage = () => {
  resultMessage.value = undefined;
  newLanguage.value = undefined;
  updateLanguage.value = undefined;
  fetch("http://localhost/api/languages", {
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
          langId: obj.langId,
          language: obj.language,
          isActive: obj.isActive === "1" ? "Active" : "Inactive",
        };
      });
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};
</script>
    
<style></style>