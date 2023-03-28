<template>
  <div class="bg-red-500 home">
    <commonNavbar />
    <hero-section />
    <main class="flex flex-col items-center justify-center bg-slate-200">
      <section class="max-w-5xl m-6">
        <h1 class="m-2 text-3xl text-center text-teal-600">About us</h1>
        <p class="mb-4">
          Founded in Denmark, this library was originally established to serve
          the Hungarian community. Although the majority of the books in the
          library are in Hungarian, our aim is to become more diverse and offer
          books in multiple languages. Our mission is to provide children with
          access to books in their native tongue while they are living in a
          Danish environment. We firmly believe that learning their parents'
          language is just as important as learning Danish.
        </p>
        <p class="mb-4">
          The importance of mother tongue in bilingual families or those living
          in a foreign country cannot be overstated. Mother tongue, also known
          as the first language, is a crucial part of a child's identity and
          cultural heritage. It provides a sense of belonging and connection to
          their roots, which is especially important for children growing up in
          a new environment.
        </p>
        <p class="mb-4">
          Moreover, learning the mother tongue helps children to develop their
          cognitive abilities, as it requires the brain to process information
          in different ways. It also provides them with a solid foundation for
          learning other languages and strengthens their overall language
          skills.
        </p>
        <p>
          In conclusion, promoting the mother tongue in children is vital for
          their development and well-being. By providing access to books in
          their native language, our library aims to support bilingual families
          and children living abroad in preserving their cultural heritage and
          language skills.
        </p>
      </section>
      <section class="max-w-5xl m-6">
        <h1 class="m-2 text-3xl text-center text-teal-600">
          How to Use the Online Library
        </h1>
        <p class="mb-4">
          Please note that our collection is managed by volunteers, who are
          dedicated to making your experience with us as smooth and enjoyable as
          possible.
        </p>
        <ul class="my-4 list-disc list-inside">
          <li class="font-bold">Searching the Collection</li>
          <p>
            Our collection can be freely searched to find the books you're
            looking for. Simply use the search bar or browse through our
            categories to find the books you want.
          </p>
          <li class="font-bold">Registering and Logging In</li>
          <p>
            To issue a book from our library, you need to register by clicking
            on the "Register/Login" menu and filling out the contact form. Your
            address is an important piece of information, as the books will be
            sent to you by post (rarely personal pick up is possible).
          </p>
          <p>
            Once you have completed the registration process, we will send you
            an activation code that you will be required to provide during your
            initial login. It is important to be aware that the email containing
            the activation code may end up in your spam folder. Additionally,
            please note that you will have only 24 hours to activate your
            account.
          </p>
          <li class="font-bold">Booking a Book</li>
          <p>
            Once you've logged in, you can access your personal page where you
            can again search for books and issue it by clicking the "Issue Book"
            button. Your booking request will be reviewed by an admin, who will
            send the book to your address.
          </p>
          <li class="font-bold">Returning the Book</li>
          <p>
            The book must be returned within 3 weeks of receiving it, or a daily
            fine will be added to your account (10 kr per day). Please make sure
            to return the book in good condition.
          </p>
        </ul>
        <p class="mb-2">
          If you have any questions or concerns about our services, please do
          not hesitate to contact us. Our team is here to assist you and make
          sure that your experience with our library is a positive one. Whether
          you need help finding a specific book or have feedback to share, we
          would love to hear from you.
        </p>
        <p class="mb-2">
          You can reach us by emailing us at
          <span class="font-bold"> fromlabtoweb.projects@gmail.com</span>. Our
          friendly and knowledgeable staff will be more than happy to help you
          with any inquiries you may have.
        </p>
        <p>
          Thank you for choosing A Multilingual Children's Library Online, and
          we look forward to serving you soon!
        </p>
      </section>
    </main>
    <footer class="p-2 text-sm text-center bg-slate-100">
      <p>Impressum</p>
      <p>Made and designed by Viktoria Bors-Pajuste.</p>
      <p>Exam project for Backend module at ProOktatas.</p>
    </footer>
  </div>
</template>

<script setup>
// @ is an alias to /src
import commonNavbar from "@/components/common/common-navbar.vue";
import heroSection from "../components/sections/hero-section.vue";
</script>
