<template>
  <header>
    <div class="relative p-12 overflow-hidden text-center bg-center bg-no-repeat bg-cover " :style="style">
      <div class="absolute top-0 bottom-0 left-0 right-0 w-full h-full overflow-hidden bg-fixed "
        style="background-color: rgba(17, 94, 89, 0.3);">
        <div class="flex items-center justify-center h-full">
          <div class="text-white">
            <h2 class="mb-4 text-4xl font-semibold">
              Nurture their roots, unleash their potential
            </h2>
            <h4 class="mb-6 text-xl font-semibold">
              Let us assist you with that
            </h4>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
const style = `background-image: url('/img/pexels-lina-kivaka-1741230.jpg');
               height: 300px;`;
</script>

<style></style>
