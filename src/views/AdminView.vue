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
  <section
    v-if="results"
    id="dashboard"
    class="flex flex-col flex-wrap justify-center gap-2 m-4 md:flex-row"
  >
    <div v-for="{ headline, number } in results" :key="headline">
      <base-card :text="headline" :number="number"></base-card>
    </div>
  </section>
</template>
    
<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseCard from "@/components/common/base-card.vue";
import { onMounted, ref } from "vue";

let countResult = ref(undefined);
let results = ref(undefined);

onMounted(() => {
  fetch("http://localhost/api/admin/counts", {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      countResult.value = data.reduce((acc, curr) => {
        acc[curr.table_name] = curr.row_count;
        return acc;
      }, {});
      results.value = [
        {
          headline: "Total Books",
          number: countResult.value.books,
        },
        {
          headline: "Total Authors",
          number: countResult.value.authors,
        },
        {
          headline: "Total Book Issued",
          number: countResult.value.issuedbooks,
        },
        {
          headline: "Total Users",
          number: countResult.value.users,
        },
      ];
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
});
</script>
    
<style></style>