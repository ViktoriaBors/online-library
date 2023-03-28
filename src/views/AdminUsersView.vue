// Rename table and variable
<template>
  <common-navbar :to="navOptions"></common-navbar>
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
          :key="data.userId"
        >
          <td class="">
            <button
              :data-userId="data.userId"
              @click="changeUserBanStatus(data.userId)"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              {{ data.isBanned === "Banned" ? "Unban" : "Ban" }}
            </button>
            <button
              :data-userId="data.userId"
              @click="changeUserApproveStatus(data.userId)"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              {{ data.isApproved === "Approved" ? "Disapprove" : "Approve" }}
            </button>
            <button
              :data-userId="data.userId"
              @click="deleteUser(data.userId)"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              Delete
            </button>
            <button
              :data-userId="data.userId"
              @click="editUserRole(data.userId)"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              Edit role
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="max-w-md mx-2 mb-4 sm:mx-auto" v-if="!resultMessage">
    <form class="" @submit.prevent>
      <div class="flex flex-col md:flex-row">
        <div v-if="updateUser">
          <div class="text-center">
            <p>New Role for {{ updateUser.name }}</p>
            <base-select
              :options="roles"
              :value="updateUser.role"
              @change="updateUser.role = $event"
            ></base-select>
          </div>
          <base-button @click="changeUserRole()"> Save Changes </base-button>
          <base-button @click="updateUser = undefined"> Cancel </base-button>
        </div>
      </div>
    </form>
  </div>
  <p
    class="flex justify-center mt-2 text-2xl font-bold leading-tight"
    :class="resultMessage ? 'text-teal-800' : 'text-red-900'"
  >
    {{ resultMessage ? resultMessage : errorMessage }}
  </p>
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

const headline = ["User Id", "Name", "Email", "Role", "Approve", "Ban"];
const roles = [
  { value: "user", label: "User" },
  { value: "admin", label: "Admin" },
  { value: "sudo", label: "Superuser" },
];

const result = ref([]);
let resultMessage = ref(undefined);
let errorMessage = ref(undefined);
const updateUser = ref(undefined);
let userToChangeId = ref(undefined);

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

const changeUserBanStatus = (id) => {
  const userId = Number(id);
  const status = result.value.find((user) => {
    return user.userId == userId;
  }).isBanned;
  const body = {
    userId,
    isBanned: status,
  };
  fetch("http://localhost/api/admin/userBanStatus", {
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
        resultMessage.value = "User ban status update was successful.";
      } else {
        resultMessage.value = "User ban status update failed.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const changeUserApproveStatus = (id) => {
  const userId = Number(id);
  const status = result.value.find((user) => {
    return user.userId == userId;
  }).isApproved;
  const body = {
    userId,
    isApproved: status,
  };
  fetch("http://localhost/api/admin/userApproveStatus", {
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
        resultMessage.value = "User approve status update was successful.";
      } else {
        resultMessage.value = "User approve status update failed.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const deleteUser = (id) => {
  const userId = Number(id);
  const body = {
    userId,
  };
  fetch("http://localhost/api/admin/deleteUser", {
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
        resultMessage.value = "User deleted successfully.";
      } else {
        resultMessage.value = "user delete failed.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const editUserRole = (id) => {
  userToChangeId.value = id;
  fetch("http://localhost/api/getUser/" + id, {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      updateUser.value = data;
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const changeUserRole = () => {
  const userId = updateUser.value.userId;
  const role = updateUser.value.role;
  const body = {
    userId,
    role,
  };
  fetch("http://localhost/api/admin/changeUserRole", {
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
        resultMessage.value = "User role update was successful.";
      } else {
        resultMessage.value = "User role update failed.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const cancelChangeBookDetails = () => {
  title.value = undefined;
  author.value = undefined;
  language.value = undefined;
  category.value = undefined;
  copies.value = undefined;
};

const addNewBook = () => {
  if (
    !title.value ||
    !author.value ||
    !language.value ||
    !category.value ||
    !availability.value ||
    !description.value
  ) {
    errorMessage.value = "Please fill up the missing field!";
    return;
  }
  const body = {
    title: title.value,
    author: author.value,
    language: language.value,
    category: category.value,
    availability: copies.value,
    description: description.value,
  };
  fetch("http://localhost/api/admin/newBook", {
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
  fetch("http://localhost/api/admin/users", {
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
          userId: obj.userId,
          name: obj.name,
          email: obj.email,
          role: obj.role,
          isApproved: obj.isApproved == "1" ? "Approved" : "Disapproved",
          isBanned: obj.isBanned == "1" ? "Banned" : "Not Banned",
        };
      });
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
});

const refreshPage = () => {
  resultMessage.value = undefined;
  updateUser.value = undefined;
  fetch("http://localhost/api/admin/users", {
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
          userId: obj.userId,
          name: obj.name,
          email: obj.email,
          role: obj.role,
          isApproved: obj.isApproved == "1" ? "Approved" : "Disapproved",
          isBanned: obj.isBanned == "1" ? "Banned" : "Not Banned",
        };
      });
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};
</script>
    
<style></style>