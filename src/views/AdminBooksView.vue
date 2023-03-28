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
          :key="data.bookId"
        >
          <td class="px-6">
            <button
              :data-bookId="data.bookId"
              @click="changeBookStatus(data.bookId)"
              class="block text-sm leading-tight text-teal-800 uppercase md:text-md"
            >
              {{ data.isActive === "Active" ? "Disable" : "Enable" }}
            </button>
            <button
              :data-bookId="data.bookId"
              @click="editBook(data.bookId)"
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
  <div class="max-w-xl mx-2 mb-4 sm:mx-auto" v-if="!resultMessage">
    <form v-if="updateBook" @submit.prevent class="">
      <div class="grid gap-2 mb-6 md:grid-cols-2">
        <div>
          <p class="mt-2 ml-1">Book Title</p>
          <base-input
            :value="title"
            :class="'w-full'"
            @input="title = $event"
            @change="title = $event"
            placeholder="Book Title"
            aria-label="book title"
            required
          >
          </base-input>
        </div>
        <div>
          <p class="mt-2 ml-1">No. of copies</p>
          <base-input
            :value="copies"
            :class="'w-full'"
            :type="'number'"
            @input="copies = $event"
            @change="copies = $event"
            placeholder="copies"
            aria-label="copies"
            required
          >
          </base-input>
        </div>
      </div>
      <div class="grid grid-cols-1 gap-2 mb-6 sm:grid-cols-3">
        <div>
          <p>Choose a category</p>
          <base-select
            :options="getCategories"
            :value="category"
            @change="category = $event"
          ></base-select>
        </div>
        <div>
          <p>Choose a language</p>
          <base-select
            :options="getLanguages"
            :value="language"
            @change="language = $event"
          ></base-select>
        </div>
        <div>
          <p>Choose an author</p>
          <base-select
            :options="getAuthors"
            :value="author"
            @change="author = $event"
          ></base-select>
        </div>
      </div>
      <base-button @click="changeBookDetails()"> Save Changes </base-button>
      <base-button @click="cancelChangeBookDetails()"> Cancel </base-button>
    </form>
    <form @submit.prevent v-if="!updateBook" class="max-w-72">
      <div class="grid gap-2 mb-6 md:grid-cols-2">
        <div>
          <p class="mt-2 ml-1">Book Title</p>
          <base-input
            :value="title"
            :class="'w-full'"
            @input="title = $event"
            @change="title = $event"
            placeholder="Book Title"
            aria-label="book title"
            required
          >
          </base-input>
        </div>
        <div>
          <p class="mt-2 ml-1">No. of copies</p>
          <base-input
            :value="copies"
            :class="'w-full'"
            :type="'number'"
            @input="copies = $event"
            @change="copies = $event"
            placeholder="copies"
            aria-label="copies"
            required
          >
          </base-input>
        </div>
      </div>
      <div class="grid grid-cols-1 gap-2 mb-6 sm:grid-cols-3">
        <div>
          <p>Choose a category</p>
          <base-select
            :options="getCategories"
            :value="category"
            @change="category = $event"
          ></base-select>
        </div>
        <div>
          <p>Choose a language</p>
          <base-select
            :options="getLanguages"
            :value="language"
            @change="language = $event"
          ></base-select>
        </div>
        <div>
          <p>Choose an author</p>
          <base-select
            :options="getAuthors"
            :value="author"
            @change="author = $event"
          ></base-select>
        </div>
      </div>
      <div>
        <p>Description</p>
        <textarea rows="3" cols="30" v-model="description" />
      </div>
      <base-button @click="addNewBook()"> Save New Book </base-button>
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
import baseSelect from "@/components/common/base-select.vue";
import { ref, onMounted } from "vue";

const headline = [
  "Book Id",
  "Title",
  "Author",
  "Language",
  "Category",
  "Copies",
  "Status",
];

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

const result = ref([]);
let resultMessage = ref(undefined);
let errorMessage = ref(undefined);
const newBook = ref(undefined);
const updateBook = ref(undefined);
const bookToChangeId = ref(undefined);
let title = ref(undefined);
let author = ref(undefined);
let language = ref(undefined);
let copies = ref(undefined);
let category = ref(undefined);
let description = ref(undefined);
let getAuthors = ref(undefined);
let getCategories = ref(undefined);
let getLanguages = ref(undefined);

const changeBookStatus = (id) => {
  const bookId = Number(id);
  const status = result.value.find((book) => {
    return book.bookId == bookId;
  });
  const body = {
    bookId,
    isActive: status.isActive,
  };
  fetch("http://localhost/api/admin/bookStatus", {
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
        resultMessage.value = "Book status update was successful.";
      } else {
        resultMessage.value = "Book status update failed.";
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const editBook = (id) => {
  bookToChangeId.value = id;
  fetch("http://localhost/api/book/" + id, {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      title.value = data.title;
      author.value = data.author;
      language.value = data.language;
      category.value = data.category;
      copies.value = data.availability;
      updateBook.value = true;
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};

const changeBookDetails = () => {
  if (
    !title.value ||
    !author.value ||
    !language.value ||
    !category.value ||
    !availability.value
  ) {
    errorMessage.value = "Please fill up the missing field!";
    return;
  }
  const bookId = result.value.find((book) => {
    return book.bookId == bookToChangeId.value;
  }).bookId;
  const body = {
    bookId,
    title: title.value,
    author: author.value,
    language: language.value,
    category: category.value,
    availability: copies.value,
  };
  fetch("http://localhost/api/admin/book", {
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
        resultMessage.value = "Book update was successful.";
      } else {
        resultMessage.value = "Book update failed.";
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
  updateBook.value = undefined;
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
  fetch("http://localhost/api/books", {
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
          bookId: obj.bookId,
          title: obj.title,
          author: obj.author,
          language: obj.language,
          category: obj.category,
          copies: obj.availability,
          isActive: obj.isActive === "1" ? "Active" : "Inactive",
        };
      });
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
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
      getCategories.value = data.map((category) => {
        return { value: category.categoryId, label: category.categoryName };
      });
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
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
      getLanguages.value = data.map((language) => {
        return { value: language.langId, label: language.language };
      });
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
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
      getAuthors.value = data.map((author) => {
        return { value: author.authorId, label: author.authorName };
      });
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
});

const refreshPage = () => {
  resultMessage.value = undefined;
  title.value = undefined;
  author.value = undefined;
  language.value = undefined;
  category.value = undefined;
  copies.value = undefined;
  updateBook.value = undefined;
  description.value = undefined;
  fetch("http://localhost/api/books", {
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
          bookId: obj.bookId,
          title: obj.title,
          author: obj.author,
          language: obj.language,
          category: obj.category,
          copies: obj.availability,
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