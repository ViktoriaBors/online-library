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
          :key="data.categoryId"
        >
          <td class="px-6">
            <button
              :data-categoryId="data.categoryId"
              @click="changeCategoryStatus(data.categoryId)"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              {{ data.isActive === "Active" ? "Disable" : "Enable" }}
            </button>
            <button
              :data-categoryId="data.categoryId"
              @click="editCategoryName(data.categoryId)"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              Edit
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <p class="my-4 text-2xl font-bold leading-tight text-center text-teal-800">
    {{ resultMessage }}
  </p>
  <div class="max-w-md mx-2 mb-4 sm:mx-auto" v-if="!resultMessage">
    <form class="" @submit.prevent>
      <div class="flex flex-col md:flex-row">
        <div v-if="updateCategoryName">
          <p>Category Name</p>
          <base-input
            placeholder=" "
            required
            :value="updateCategoryName"
            @input="updateCategoryName = $event"
            @change="updateCategoryName = $event"
          />
          <base-button @click="changeCategoryName()">
            Save Changes
          </base-button>
          <base-button @click="updateCategoryName = undefined">
            Cancel
          </base-button>
        </div>
        <div v-if="!updateCategoryName">
          <p>Category Name</p>
          <base-input
            placeholder=" "
            required
            :value="newCategoryName"
            :class="'md:flex-1'"
            @input="newCategoryName = $event"
            @change="newCategoryName = $event"
          />
          <base-button @click="addNewCategory()">
            Add new category
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

const headline = ["Category Id", "Name", "Status"];

const result = ref([]);
let resultMessage = ref(undefined);
let newCategoryName = ref(undefined);
const updateCategoryName = ref(undefined);
let categoryToChangeId = ref(undefined);

const changeCategoryStatus = (id) => {
  const categoryId = Number(id);
  const status = result.value.find((book) => {
    return book.categoryId == categoryId;
  });
  const body = {
    categoryId,
    isActive: status.isActive,
  };
  fetch("http://localhost/api/admin/categoryStatus", {
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
        resultMessage.value = "Category status update was successful.";
      } else {
        resultMessage.value = "Category status update failed.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const editCategoryName = (id) => {
  categoryToChangeId.value = id;
  fetch("http://localhost/api/category/" + id, {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      updateCategoryName.value = data.categoryName;
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const changeCategoryName = () => {
  const categoryId = result.value.find((category) => {
    return category.categoryId == categoryToChangeId.value;
  }).categoryId;
  const body = {
    categoryId,
    categoryName: updateCategoryName.value,
  };
  fetch("http://localhost/api/admin/categoryName", {
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
        resultMessage.value = "Category name update was successful.";
      } else {
        resultMessage.value = "Category name update failed.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const addNewCategory = () => {
  const body = {
    categoryName: newCategoryName.value,
  };
  fetch("http://localhost/api/admin/newCategory", {
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
        resultMessage.value = "New category has been added successfully.";
      } else {
        resultMessage.value = "Failed to add new category.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

onMounted(() => {
  fetch("http://localhost/api/categories", {
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
          categoryId: obj.categoryId,
          categoryName: obj.categoryName,
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
  newCategoryName.value = undefined;
  updateCategoryName.value = undefined;
  fetch("http://localhost/api/categories", {
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
          categoryId: obj.categoryId,
          categoryName: obj.categoryName,
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