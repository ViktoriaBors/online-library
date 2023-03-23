<template>
  <common-navbar
    :to="['/user', '/user/issue', '/user/profile', '/logout']"
  ></common-navbar>
  <section class="flex flex-col items-center justify-center m-4 mt-6">
    <form action="" @submit.prevent class="max-w-72">
      <div class="grid gap-6 mb-6 md:grid-cols-2">
        <div>
          <p class="mt-2 ml-1">First name</p>
          <base-input
            :value="firstName"
            :class="isDisabled ? 'bg-gray-300 w-72' : 'w-72'"
            @input="firstName = $event"
            @change="firstName = $event"
            placeholder="First Name"
            aria-label="first name"
            required
            :disabled="isDisabled"
          >
          </base-input>
        </div>
        <div>
          <p class="mt-2 ml-1">Last name</p>
          <base-input
            :value="lastName"
            :class="isDisabled ? 'bg-gray-300 w-72' : 'w-72'"
            @input="lastName = $event"
            @change="lastName = $event"
            placeholder="Last Name"
            aria-label="last name"
            required
            :disabled="isDisabled"
          >
          </base-input>
        </div>
      </div>
      <div class="grid gap-6 mb-6 md:grid-cols-3">
        <div>
          <p class="mt-2 ml-1">Postal Code</p>
          <base-input
            :value="postalCode"
            :class="
              isDisabled ? 'bg-gray-300 w-full md:w-24' : 'w-full md:w-24'
            "
            :type="'number'"
            @input="postalCode = $event"
            @change="postalCode = $event"
            aria-label="postal code"
            required
            :disabled="isDisabled"
          >
          </base-input>
        </div>
        <div>
          <p class="mt-2 ml-1">City</p>
          <base-input
            :value="city"
            :class="
              isDisabled ? 'bg-gray-300 w-full md:w-68' : 'w-full md:w-68'
            "
            @input="city = $event"
            @change="city = $event"
            placeholder="City"
            aria-label="city"
            required
            :disabled="isDisabled"
          >
          </base-input>
        </div>
        <div>
          <p class="mt-2 ml-1">Address</p>
          <base-input
            :value="address"
            :class="
              isDisabled ? 'bg-gray-300 w-full md:w-68' : 'w-full md:w-68'
            "
            @input="address = $event"
            @change="address = $event"
            placeholder="Street, street number"
            aria-label="address"
            required
            :disabled="isDisabled"
          >
          </base-input>
        </div>
      </div>
      <base-button @click="editUser" :class="'mt-2 mr-4'">{{
        isDisabled ? "Edit" : "Cancel"
      }}</base-button>
      <base-button :class="'mt-2'" @click="updateUser" v-if="!isDisabled"
        >Save</base-button
      >
    </form>
    <p class="mt-2 text-2xl font-bold leading-tight text-teal-800">
      {{ result }}
    </p>
  </section>
</template>

<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseButton from "@/components/common/base-button.vue";
import baseInput from "@/components/common/base-input.vue";
import { ref, onMounted } from "vue";

let firstName = ref(undefined);
let lastName = ref(undefined);
let postalCode = ref(undefined);
let city = ref(undefined);
let address = ref(undefined);
let isDisabled = ref(true);
let result = ref(undefined);

const editUser = () => {
  isDisabled.value = !isDisabled.value;
};

onMounted(() => {
  const user = localStorage.getItem("user");
  const userParse = JSON.parse(user);
  const userId = userParse.sub;
  fetch("http://localhost/api/user/getUser/" + userId, {
    method: "GET",
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((data) => {
      firstName.value = data.name.split(" ")[0];
      lastName.value = data.name.split(" ")[1];
      postalCode.value = data.address.split(" ")[0];
      city.value = data.address.split(" ")[1];
      const [_, part2, part3] = data.address.split(" ");
      address.value = data.address.slice(data.address.indexOf(part3));
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
});

const updateUser = () => {
  const user = localStorage.getItem("user");
  const userParse = JSON.parse(user);
  const userId = userParse.sub;
  const body = {
    userId: Number(userId),
    name: firstName.value + " " + lastName.value,
    address: postalCode.value + " " + city.value + " " + address.value,
  };
  fetch("http://localhost/api/user/updateUser", {
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
        result.value = "Update was successful";
        isDisabled.value = true;
      }
    })
    .catch((error) => {
      console.error("There was a problem with the fetch operation:", error);
    });
};
</script>