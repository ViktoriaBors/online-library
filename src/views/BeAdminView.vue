<template>
  <div class="admin">
    <common-navbar />
    <section class="flex flex-col items-center justify-center">
      <div class="max-w-5xl m-6">
        <h1 class="pb-4 m-2 text-3xl text-center text-teal-600">
          Join Our Team as an Admin at the Multilingual Children's Library
        </h1>
        <p class="mt-2 mb-4">
          We are seeking dedicated individuals to help us at the Multilingual
          Children's Library. As an admin, you will have access to the admin
          page where you will review book issues, check in returned books,
          communicate with users who have not yet returned a book, and assist
          our customers by responding to their emails.
        </p>
        <p class="mb-4">
          Being a part of this important community is a volunteer opportunity
          (without payment) but offers immeasurable benefits, including the
          chance to grow your network and bring happiness to children. To become
          an admin, simply write a brief letter introducing yourself and
          explaining how you can contribute to our cause. Please send your email
          to <span class="font-bold"> multilingualChildren@test.com</span>.
        </p>
        <p class="mb-4">
          We look forward to hearing from you and welcoming you to our team!
        </p>
      </div>
    </section>
    <section class="flex flex-col items-center justify-center">
      <p
        id="login"
        class="block text-3xl font-bold leading-tight text-teal-800 uppercase"
      >
        Log in as an Admin
      </p>
      <form action="" @submit.prevent class="max-w-72">
        <div>
          <p class="mt-2 ml-1">E-mail address</p>
          <base-input
            :value="loginAdminEmail"
            :class="'w-72'"
            @input="loginAdminEmail = $event"
            @change="loginAdminEmail = $event"
            placeholder="Email"
            aria-label="Email"
            required
          ></base-input>
        </div>
        <div>
          <p class="mt-2 ml-1">Password</p>
          <base-input
            :value="loginAdminPassword"
            :class="'w-72'"
            :type="'password'"
            @input="loginAdminPassword = $event"
            @change="loginAdminPassword = $event"
            placeholder="Password"
            aria-label="Password"
            required
          ></base-input>
        </div>
        <base-button :class="'mt-2'" @click="loginAdmin">Log in</base-button>
      </form>
      <p class="text-lg font-bold text-center text-red-900">
        {{ errorLogin }}
      </p>
    </section>
  </div>
</template>

<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseButton from "@/components/common/base-button.vue";
import baseInput from "@/components/common/base-input.vue";
import jwt_decode from "jwt-decode";
import { ref } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

let loginAdminEmail = ref(undefined);
let loginAdminPassword = ref(undefined);
let errorLogin = ref(undefined);

const loginAdmin = () => {
  errorLogin.value = undefined;
  if (!loginAdminEmail.value || !loginAdminPassword.value) {
    errorLogin.value = "Please fill up the missing field!";
    return;
  }
  const body = {
    email: loginAdminEmail.value,
    password: loginAdminPassword.value,
  };
  fetch("http://localhost/api/loginAdmin", {
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
      if (data.result === "error") {
        errorLogin.value = data.message;
      } else {
        const token = data.token;
        const decoded = jwt_decode(token);
        localStorage.setItem("token", token);
        localStorage.setItem("admin", JSON.stringify(decoded));
        router.push({ path: "/admin" });
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};
</script>

<style></style>
