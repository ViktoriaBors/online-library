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
          :key="data.authorId"
        >
          <td class="px-6">
            <button
              :data-authorId="data.authorId"
              @click="changeAuthorStatus(data.authorId)"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              {{ data.isActive === "Active" ? "Disable" : "Enable" }}
            </button>
            <button
              :data-authorId="data.authorId"
              @click="editAuthorName(data.authorId)"
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
        <div v-if="updateAuthorName">
          <p>Category Name</p>
          <base-input
            placeholder=" "
            required
            :value="updateAuthorName"
            @input="updateAuthorName = $event"
            @change="updateAuthorName = $event"
          />
          <base-button @click="changeAuthorName()"> Save Changes </base-button>
          <base-button @click="updateAuthorName = undefined">
            Cancel
          </base-button>
        </div>
        <div v-if="!updateAuthorName">
          <p>Category Name</p>
          <base-input
            placeholder=" "
            required
            :value="newAuthorName"
            :class="'md:flex-1'"
            @input="newAuthorName = $event"
            @change="newAuthorName = $event"
          />
          <base-button @click="addNewAuthor()"> Add new category </base-button>
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

const headline = ["Author Id", "Author", "Status"];

const result = ref([]);
let resultMessage = ref(undefined);
let errorMessage = ref(undefined);
let newAuthorName = ref(undefined);
const updateAuthorName = ref(undefined);
let authorToChangeId = ref(undefined);

const changeAuthorStatus = (id) => {
  const authorId = Number(id);
  const status = result.value.find((book) => {
    return book.authorId == authorId;
  });
  const body = {
    authorId,
    isActive: status.isActive,
  };
  fetch("http://localhost/api/admin/authorStatus", {
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
        resultMessage.value = "Author status update was successful.";
      } else {
        resultMessage.value = "Author status update failed.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const editAuthorName = (id) => {
  authorToChangeId.value = id;
  fetch("http://localhost/api/author/" + id, {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      updateAuthorName.value = data.authorName;
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const changeAuthorName = () => {
  if (!updateAuthorName.value) {
    errorMessage.value = "Please fill up the missing field!";
    return;
  }
  const authorId = result.value.find((author) => {
    return author.authorId == authorToChangeId.value;
  }).authorId;
  const body = {
    authorId,
    authorName: updateAuthorName.value,
  };
  fetch("http://localhost/api/admin/authorName", {
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
        resultMessage.value = "Author name update was successful.";
      } else {
        resultMessage.value = "Author name update failed.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const addNewAuthor = () => {
  if (!newAuthorName.value) {
    errorMessage.value = "Please fill up the missing field!";
    return;
  }
  const body = {
    authorName: newAuthorName.value,
  };
  fetch("http://localhost/api/admin/newAuthor", {
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
        resultMessage.value = "New author has been added successfully.";
      } else {
        resultMessage.value = "Failed to add new author.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

onMounted(() => {
  fetch("http://localhost/api/authors", {
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
          authorId: obj.authorId,
          authorName: obj.authorName,
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
  newAuthorName.value = undefined;
  updateAuthorName.value = undefined;
  fetch("http://localhost/api/authors", {
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
          authorId: obj.authorId,
          authorName: obj.authorName,
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