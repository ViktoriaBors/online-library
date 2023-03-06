<template>
    <common-navbar
        :to="['/admin', '/admin/categories', '/admin/languages', '/admin/authors', '/admin/books', '/admin/users', '/admin/issues', '/logout']"></common-navbar>
    <section id="dashboard" class="flex flex-col flex-wrap justify-center gap-2 m-4 md:flex-row">
        <div v-for="{ headline, number } in results" :key="headline">
            <base-card :text="headline" :number="number"></base-card>
        </div>
    </section>
</template>
    
<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseCard from "@/components/common/base-card.vue";

let results = [
    {
        headline: 'Total Books',
        number: '15'
    },
    {
        headline: 'Total Authors',
        number: '13'
    },
    {
        headline: 'Total Book Issued',
        number: '5'
    },
    {
        headline: 'Total Users',
        number: '2'
    }
]




</script>
    
<style></style>