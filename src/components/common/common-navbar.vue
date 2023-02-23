<template>
  <nav
    class="
      bg-slate-100
      border-gray-200
      px-2
      sm:px-4
      py-2.5
      rounded
      dark:bg-gray-900
    "
  >
    <div class="container flex flex-wrap items-center justify-between mx-auto">
      <div class="flex items-center">
        <img
          src="@/assets/img/logo.png"
          class="h-6 mr-3 sm:h-9"
          alt="Library Logo"
        />
        <span
          class="self-center text-xl font-semibold text-teal-700 whitespace-nowrap dark:text-white"
          >A Multilingual Children's <br />
          (online) Library</span
        >
      </div>
      <button
        type="button"
        @click="isOpen = !isOpen"
        class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
        aria-controls="navbar-default"
        aria-expanded="false"
      >
        <span class="sr-only">Open main menu</span>
        <svg
          class="w-6 h-6"
          aria-hidden="true"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill-rule="evenodd"
            d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
            clip-rule="evenodd"
          ></path>
        </svg>
      </button>
      <div
        :class="isOpen ? 'block' : 'hidden'"
        class="w-full md:block md:w-auto"
        id="navbar-default"
      >
        <ul
          class="flex flex-col p-4 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700"
        >
        <router-link
        v-for="menu in props.to" :key="props.to"
        :to="menu"
        v-slot="{ href, route, navigate, isActive, isExactActive }"
        custom
        >
        <li
              class="block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:border-0 hover:text-teal-700 dark:text-gray-400 md:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent"
              :class="[
                isActive && 'router-link-active',
                isExactActive && 'router-link-exact-active',
              ]"
            >
              <a class="p-4" :href="href" @click="navigate">{{ route.name }}</a>
            </li>
        </router-link>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from "vue";
import { defineProps } from "vue";

const props = defineProps({
  to: {
    type: Array,
    default: () => {
      return ['/', '/collection', '/register', '/adminLogin']
    }
  }
});

const isOpen = ref(false);


</script>

<style>
.router-link-active,
.router-link-exact-active {
  background-color: rgb(229 231 235);
  color: rgb(19 78 74);
  font-weight: 600;
}
</style>
