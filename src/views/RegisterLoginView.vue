<template>
  <div class="register">
    <common-navbar />
    <section class="flex flex-col items-center justify-center m-4">
     <p class="block text-3xl font-bold leading-tight text-teal-800 uppercase cursor-pointer"
     @click="isLoginOpen =!isLoginOpen"
     >
     Log in</p>
     <form action=""
     @submit.prevent
     v-if="isLoginOpen"
     class="max-w-72"
     >
     <div>
     <p class="mt-2 ml-1">E-mail address</p>
     <base-input
     :value="loginEmail"
     :class="'w-72'"
     @input="loginEmail = $event"
     @change = "loginEmail = $event"
     placeholder="Email" aria-label="Email"
     required
     ></base-input>
     </div>
     <div>
     <p class="mt-2 ml-1">Password</p>
     <base-input
     :value="loginPassword"
     :class="'w-72'"
     :type="'password'"
     @input="loginPassword = $event"
     @change = "loginPassword = $event"
     placeholder="Password" aria-label="Password"
     required
     ></base-input>
     </div>
     <base-button :class="'mt-2'"
     @click="redirect">Log in</base-button>
     </form>
    </section>

    <section class="flex flex-col items-center justify-center m-4 mt-6">
     <p class="block text-3xl font-bold leading-tight text-teal-800 uppercase cursor-pointer"
     @click="isRegisterOpen =!isRegisterOpen"
     >
     Click here to Register</p>
     <form action=""
     @submit.prevent
     v-if="isRegisterOpen"
     class="max-w-72"
     >
     <div class="grid gap-6 mb-6 md:grid-cols-2">
        <div>
          <p class="mt-2 ml-1">First name</p>
          <base-input
          :value="firstName"
          :class="'w-72'"
          @input="firstName = $event"
          @change = "firstName = $event"
          placeholder="First Name" aria-label="first name"
          required>
          </base-input>           
        </div>
        <div>
          <p class="mt-2 ml-1">Last name</p>
          <base-input
          :value="lastName"
          :class="'w-72'"
          @input="lastName = $event"
          @change = "lastName = $event"
          placeholder="Last Name" aria-label="last name"
          required>
          </base-input>           
        </div>
      </div>
        <div class="grid gap-6 mb-6 md:grid-cols-3">
           <div>
            <p class="mt-2 ml-1">Postal Code</p>
            <base-input
            :value="postalCode"
            :class="'w-full md:w-24'"
            :type="'number'"
            @input="postalCode = $event"
            @change = "postalCode = $event"
            aria-label="postal code"
            required>
            </base-input>    
           </div>
           <div>
            <p class="mt-2 ml-1">City</p>
            <base-input
            :value="city"
            :class="'w-full md:w-68'"
            @input="city = $event"
            @change = "city = $event"
            placeholder="City" aria-label="city"
            required>
            </base-input>    
           </div>
           <div>
            <p class="mt-2 ml-1">Address</p>
            <base-input
            :value="address"
            :class="'w-full md:w-68'"
            @input="address = $event"
            @change = "address = $event"
            placeholder="Street, street number" aria-label="address"
            required>
            </base-input>    
           </div>
        </div>      
     <div>
     <p class="mt-2 ml-1">E-mail address</p>
     <base-input
     :value="registerEmail"
     :class="'w-full'"
     @input="registerEmail = $event"
     @change = "registerEmail = $event"
     placeholder="Email" aria-label="Email"
     ></base-input>
     </div>
     <div>
     <p class="mt-2 ml-1">Password</p>
     <base-input
     :value="registerPassword"
     :class="'w-full'"
     :type="'password'"
     @input="registerPassword = $event"
     @change = "registerPassword = $event"
     placeholder="Password" aria-label="Password"
     ></base-input>
     </div>
     <base-button :class="'mt-2'">Log in</base-button>
     </form>
    </section>
  </div>
</template>

<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseButton from "@/components/common/base-button.vue";
import baseInput from "@/components/common/base-input.vue";
import { ref } from 'vue';
import { useRouter } from 'vue-router'


let isLoginOpen = ref(true);
let isRegisterOpen = ref(false);
let loginEmail = ref(undefined);
let loginPassword = ref(undefined);
let registerEmail = ref(undefined);
let firstName = ref(undefined);
let lastName = ref(undefined);
let postalCode = ref(undefined);
let city = ref(undefined);
let address = ref(undefined);

const router = useRouter()
let redirect = (()=>{
  router.push({ path: '/user' });
})

</script>

<style></style>
