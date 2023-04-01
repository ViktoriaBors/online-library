<template>
  <div class="register">
    <common-navbar />
    <section class="flex flex-col items-center justify-center m-4">
      <p
        class="block text-3xl font-bold leading-tight text-teal-800 uppercase cursor-pointer"
        @click="isLoginOpen = !isLoginOpen"
      >
        Log in
      </p>
      <form action="" @submit.prevent v-if="isLoginOpen" class="max-w-72">
        <div>
          <p class="mt-2 ml-1">E-mail address</p>
          <base-input
            :value="loginEmail"
            :class="'w-72'"
            @input="loginEmail = $event"
            @change="loginEmail = $event"
            placeholder="Email"
            aria-label="Email"
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
            @change="loginPassword = $event"
            placeholder="Password"
            aria-label="Password"
            required
          ></base-input>
        </div>
        <base-button :class="'my-2'" @click="loginUser">Log in</base-button>
      </form>
      <div>
        <p
          class="mt-2 font-bold leading-tight text-center text-teal-800 cursor-pointer text-md"
          @click="forgotPassword"
        >
          Forgot your password? Click HERE
        </p>
        <p
          class="mt-2 text-2xl font-bold leading-tight text-center"
          :class="resultLogin ? 'text-teal-800' : 'text-red-900'"
        >
          {{ resultLogin ? resultLogin : errorLogin }}
        </p>
        <div
          v-if="resultLogin || forgottenPassword"
          class="flex flex-col items-center justify-center"
        >
          <base-input
            :value="activationCode"
            :class="'w-72 mt-2'"
            :type="'text'"
            @input="activationCode = $event"
            @change="activationCode = $event"
            placeholder="Activation Code"
            aria-label="Activation Code"
            required
          ></base-input>
          <base-input
            v-if="forgottenPassword"
            :value="newPassword1"
            :class="'w-72 mt-2'"
            :type="'password'"
            @input="newPassword1 = $event"
            @change="newPassword1 = $event"
            placeholder="New Password"
            aria-label="New Password"
            required
          ></base-input>
          <base-input
            v-if="forgottenPassword"
            :value="newPassword2"
            :class="'w-72  mt-2'"
            :type="'password'"
            @input="newPassword2 = $event"
            @change="newPassword2 = $event"
            placeholder="New Password"
            aria-label="New Password"
            required
          ></base-input>
          <base-button
            :class="'my-2'"
            @click="sendActivationCode"
            v-if="!forgottenPassword"
            >Send</base-button
          >
          <base-button
            :class="'my-2'"
            @click="updatePassword"
            v-if="forgottenPassword"
            >Update password</base-button
          >
        </div>
      </div>
    </section>

    <section
      class="flex flex-col items-center justify-center m-4 mt-6"
      v-if="!forgottenPassword"
    >
      <p
        class="block text-3xl font-bold leading-tight text-center text-teal-800 uppercase cursor-pointer"
        @click="isRegisterOpen = !isRegisterOpen"
      >
        Click here to Register
      </p>
      <form action="" @submit.prevent v-if="isRegisterOpen" class="max-w-72">
        <div class="grid md:mb-6 md:gap-4 md:grid-cols-2">
          <div>
            <p class="mt-1 ml-1">First name</p>
            <base-input
              :value="firstName"
              :class="'w-72'"
              @input="firstName = $event"
              @change="firstName = $event"
              placeholder="First Name"
              aria-label="first name"
              required
            >
            </base-input>
          </div>
          <div>
            <p class="mt-1 ml-1">Last name</p>
            <base-input
              :value="lastName"
              :class="'w-72'"
              @input="lastName = $event"
              @change="lastName = $event"
              placeholder="Last Name"
              aria-label="last name"
              required
            >
            </base-input>
          </div>
        </div>
        <div class="grid md:mb-6 md:gap-2 md:grid-cols-3">
          <div>
            <p class="mt-1 ml-1">Postal Code</p>
            <base-input
              :value="postalCode"
              :class="'w-full md:w-24'"
              :type="'number'"
              @input="postalCode = $event"
              @change="postalCode = $event"
              aria-label="postal code"
              required
            >
            </base-input>
          </div>
          <div>
            <p class="mt-1 ml-1">City</p>
            <base-input
              :value="city"
              :class="'w-full md:w-68'"
              @input="city = $event"
              @change="city = $event"
              placeholder="City"
              aria-label="city"
              required
            >
            </base-input>
          </div>
          <div>
            <p class="mt-1 ml-1">Address</p>
            <base-input
              :value="address"
              :class="'w-full md:w-68'"
              @input="address = $event"
              @change="address = $event"
              placeholder="Street, street number"
              aria-label="address"
              required
            >
            </base-input>
          </div>
        </div>
        <div>
          <p class="mt-1 ml-1">E-mail address</p>
          <base-input
            :value="registerEmail"
            :class="'w-full'"
            @input="registerEmail = $event"
            @change="registerEmail = $event"
            placeholder="Email"
            aria-label="Email"
            required
          ></base-input>
        </div>
        <div>
          <p class="mt-1 ml-1">Password</p>
          <base-input
            :value="registerPassword"
            :class="'w-full'"
            :type="'password'"
            @input="registerPassword = $event"
            @change="registerPassword = $event"
            placeholder="Password"
            aria-label="Password"
            required
          ></base-input>
        </div>
        <base-button :class="'mt-2'" @click="registerNewUser"
          >Register</base-button
        >
      </form>

      <div v-if="isRegisterOpen">
        <p
          class="mt-2 text-2xl font-bold leading-tight"
          :class="resultRegistration ? 'text-teal-800' : 'text-red-900'"
        >
          {{ resultRegistration ? resultRegistration : errorRegistration }}
        </p>
      </div>
    </section>
  </div>
</template>

<script setup>
import commonNavbar from "@/components/common/common-navbar.vue";
import baseButton from "@/components/common/base-button.vue";
import baseInput from "@/components/common/base-input.vue";
import { ref } from "vue";
import { useRouter } from "vue-router";
import Swal from "sweetalert2";
import jwt_decode from "jwt-decode";

const router = useRouter();

let isLoginOpen = ref(true);
let isRegisterOpen = ref(false);
let loginEmail = ref(undefined);
let loginPassword = ref(undefined);
let forgotPasswordEmail = ref(undefined);

let errorLogin = ref(undefined);
let resultLogin = ref(undefined);
let activationCode = ref(undefined);
let forgottenPassword = ref(false);
let newPassword1 = ref(undefined);
let newPassword2 = ref(undefined);

let registerEmail = ref(undefined);
let registerPassword = ref(undefined);
let firstName = ref(undefined);
let lastName = ref(undefined);
let postalCode = ref(undefined);
let city = ref(undefined);
let address = ref(undefined);

let errorRegistration = ref(undefined);
let resultRegistration = ref(undefined);

const registerNewUser = () => {
  errorRegistration.value = undefined;
  resultRegistration.value = undefined;
  if (
    !firstName.value ||
    !lastName.value ||
    !postalCode.value ||
    !city.value ||
    !address.value ||
    !registerEmail.value ||
    !registerPassword.value
  ) {
    error.value = "Please fill up the missing field!";
    return;
  } else {
    const body = {
      name: firstName.value + " " + lastName.value,
      email: registerEmail.value,
      password: registerPassword.value,
      address: postalCode.value + " " + city.value + " " + address.value,
    };

    fetch("https://fromlabtoweb.hu/api/register", {
      method: "POST",
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
          errorRegistration.value = data.message;
        } else {
          resultRegistration.value = data.message;
          firstName.value = undefined;
          lastName.value = undefined;
          postalCode.value = undefined;
          city.value = undefined;
          address.value = undefined;
          registerEmail.value = undefined;
          registerPassword.value = undefined;
        }
      })
      .catch((error) => {
        console.error("There was a problem with the fetch operation:", error);
      });
  }
};

const loginUser = () => {
  errorLogin.value = undefined;
  resultLogin.value = undefined;
  if (!loginEmail.value || !loginPassword) {
    errorLogin.value = "Please fill up the missing field!";
    return;
  } else {
    const body = {
      email: loginEmail.value,
      password: loginPassword.value,
    };
    fetch("https://fromlabtoweb.hu/api/loginUser", {
      method: "POST",
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
          if (data.result === "activation") {
            resultLogin.value = data.message;
            return;
          } else {
            const token = data.token;
            const decoded = jwt_decode(token);
            localStorage.setItem("token", token);
            localStorage.setItem("user", JSON.stringify(decoded));
            router.push({ path: "/user" });
          }
        }
      })
      .catch((error) => {
        console.error("There was a problem with the fetch operation:", error);
      });
  }
};

const sendActivationCode = () => {
  if (!activationCode.value) {
    errorLogin.value = "Please fill up the missing field!";
  } else {
    const body = {
      email: loginEmail.value,
      activationCode: activationCode.value,
    };
    fetch("https://fromlabtoweb.hu/api/activateUser", {
      method: "POST",
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
          localStorage.setItem("user", JSON.stringify(decoded));
          router.push({ path: "/user" });
        }
      })
      .catch((error) => {
        console.error("There was a problem with the fetch operation:", error);
      });
  }
};

const forgotPassword = () => {
  if (!loginEmail.value) {
    errorLogin.value =
      "Please fill up the email field! Click to 'Forgot your password'. ";
  } else {
    errorLogin.value = undefined;
    resultLogin.value = undefined;
    forgottenPassword.value = true;
    forgotPasswordEmail.value = loginEmail.value;
    const body = {
      email: loginEmail.value,
    };
    fetch("https://fromlabtoweb.hu/api/forgotPassword", {
      method: "POST",
      credentials: "include",
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
          resultLogin.value = data.message;
        }
      })
      .catch((error) => {
        console.error("There was a problem with the fetch operation:", error);
      });
  }
};

const updatePassword = () => {
  errorLogin.value = undefined;
  resultLogin.value = undefined;
  if (
    !activationCode.value ||
    !newPassword1.value ||
    !newPassword2.value ||
    newPassword1.value !== newPassword2.value
  ) {
    errorLogin.value = "Password is not matching. ";
    return;
  } else {
    errorLogin.value = undefined;
    resultLogin.value = undefined;
    const body = {
      email: forgotPasswordEmail.value,
      password: newPassword1.value,
      code: activationCode.value,
    };
    fetch("https://fromlabtoweb.hu/api/updatePassword", {
      method: "POST",
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
          resultLogin.value = data.message;
          activationCode.value = undefined;
          newPassword1.value = undefined;
          newPassword2.value = undefined;
        }
      })
      .catch((error) => {
        console.error("There was a problem with the fetch operation:", error);
      });
  }
};
</script>

<style></style>
