(() => {
  "use strict";
  var e = {
      9651: (e, t, o) => {
        o(6992), o(8674), o(9601), o(7727);
        var r = o(5010),
          a = o(3396);
        function n(e, t) {
          var o = (0, a.up)("router-view");
          return (0, a.wg)(), (0, a.j4)(o);
        }
        var i = o(89);
        const s = {},
          l = (0, i.Z)(s, [["render", n]]),
          d = l;
        o(1539), o(8783), o(3948);
        var u = o(2483),
          c = o(1341),
          h = (0, a.uE)(
            '<div class="absolute top-0 right-0 bottom-0 left-0 w-full h-full overflow-hidden bg-fixed" style="background-color:rgba(0, 0, 0, 0.6);"><div class="flex justify-center items-center h-full"><div class="text-white"><h2 class="font-semibold text-4xl mb-4">Nurture their roots, unleash their potential</h2><h4 class="font-semibold text-xl mb-6">Let us assist you with that</h4></div></div></div>',
            1
          ),
          g = [h];
        const b = {
            __name: "hero-section",
            setup: function (e) {
              var t =
                "background-image: url('/img/pexels-lina-kivaka-1741230.jpg');\n               height: 300px;";
              return function (e, o) {
                return (
                  (0, a.wg)(),
                  (0, a.iD)("header", null, [
                    (0, a._)(
                      "div",
                      {
                        class:
                          "p-12 text-center relative overflow-hidden bg-no-repeat bg-cover bg-center",
                        style: t,
                      },
                      g
                    ),
                  ])
                );
              };
            },
          },
          m = b,
          p = m;
        var f = { class: "home bg-red-500" },
          v = (0, a.uE)(
            '<main class="bg-slate-200 flex flex-col justify-center items-center"><section class="max-w-5xl m-6"><h1 class="text-3xl text-center text-teal-600 m-2">About us</h1><p class="mb-4">Founded in Denmark, this library was originally established to serve the Hungarian community. Although the majority of the books in the library are in Hungarian, our aim is to become more diverse and offer books in multiple languages. Our mission is to provide children with access to books in their native tongue while they are living in a Danish environment. We firmly believe that learning their parents&#39; language is just as important as learning Danish.</p><p class="mb-4">The importance of mother tongue in bilingual families or those living in a foreign country cannot be overstated. Mother tongue, also known as the first language, is a crucial part of a child&#39;s identity and cultural heritage. It provides a sense of belonging and connection to their roots, which is especially important for children growing up in a new environment.</p><p class="mb-4">Moreover, learning the mother tongue helps children to develop their cognitive abilities, as it requires the brain to process information in different ways. It also provides them with a solid foundation for learning other languages and strengthens their overall language skills.</p><p>In conclusion, promoting the mother tongue in children is vital for their development and well-being. By providing access to books in their native language, our library aims to support bilingual families and children living abroad in preserving their cultural heritage and language skills.</p></section><section class="max-w-5xl m-6"><h1 class="text-3xl text-center text-teal-600 m-2">How to Use the Online Library</h1><p class="mb-4">Please note that our collection is managed by volunteers, who are dedicated to making your experience with us as smooth and enjoyable as possible.</p><ul class="list-disc list-inside my-4"><li class="font-bold">Searching the Collection</li><p>Our collection can be freely searched to find the books you&#39;re looking for. Simply use the search bar or browse through our categories to find the books you want.</p><li class="font-bold">Registering and Logging In</li><p>To issue a book from our library, you need to register by clicking on the &quot;Register/Login&quot; menu and filling out the contact form. Your address is an important piece of information, as the books will be sent to you by post (rarely personal pick up is possible).</p><li class="font-bold">Booking a Book</li><p>Once you&#39;ve logged in, you can access your personal page where you can again search for books and book it by clicking the &quot;Book&quot; button. Your booking request will be reviewed by an admin, who will approve or deny the request based on availability.</p><li class="font-bold">Receiving the Book</li><p> If your booking request is approved, the book will be sent to you by post.</p><li class="font-bold">Returning the Book</li><p>The book must be returned within 3 weeks of receiving it, or a daily fine will be added to your account. Please make sure to return the book in good condition.</p></ul><p class="mb-2">If you have any questions or concerns about our services, please do not hesitate to contact us. Our team is here to assist you and make sure that your experience with our library is a positive one. Whether you need help finding a specific book or have feedback to share, we would love to hear from you. </p><p class="mb-2"> You can reach us by emailing us at <span class="font-bold"> multilingualChildren@test.com</span>. Our friendly and knowledgeable staff will be more than happy to help you with any inquiries you may have.</p><p> Thank you for choosing A Multilingual Children&#39;s Library Online, and we look forward to serving you soon!</p></section></main><footer class="bg-slate-100 text-sm text-center p-2"><p>Impressum</p><p>Made and designed by Viktoria Bors-Pajuste.</p><p>Exam project for Backend module at ProOktatas.</p></footer>',
            2
          );
        const k = {
            __name: "HomeView",
            setup: function (e) {
              return function (e, t) {
                return (
                  (0, a.wg)(),
                  (0, a.iD)("div", f, [(0, a.Wm)(c.Z), (0, a.Wm)(p), v])
                );
              };
            },
          },
          y = k,
          x = y;
        var w = [
            { path: "/", name: "Home", component: x },
            {
              path: "/collection",
              name: "Collection",
              component: function () {
                return o.e(443).then(o.bind(o, 9403));
              },
            },
            {
              path: "/register",
              name: "Register/Login",
              component: function () {
                return o.e(443).then(o.bind(o, 4698));
              },
            },
            {
              path: "/admin",
              name: "Be Admin",
              component: function () {
                return o.e(443).then(o.bind(o, 2325));
              },
            },
          ],
          P = (0, u.p7)({ history: (0, u.PO)("/"), routes: w });
        const O = P;
        (0, r.ri)(d).use(O).mount("#app");
      },
      1341: (e, t, o) => {
        o.d(t, { Z: () => f });
        o(8309);
        var r = o(3396),
          a = o(7139);
        const n =
          "data:image/png;base64,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";
        var i = {
            class:
              "bg-slate-100 border-gray-200 px-2 sm:px-4 py-2.5 rounded dark:bg-gray-900",
          },
          s = {
            class:
              "container flex flex-wrap items-center justify-between mx-auto",
          },
          l = (0, r.uE)(
            '<div class="flex items-center"><img src="' +
              n +
              '" class="h-6 mr-3 sm:h-9" alt="Library Logo"><span class="self-center text-xl font-semibold whitespace-nowrap text-teal-700 dark:text-white">A Multilingual Children&#39;s <br> (online) Library</span></div><button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false"><span class="sr-only">Open main menu</span><svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg></button>',
            2
          ),
          d = {
            class: "hidden w-full md:block md:w-auto",
            id: "navbar-default",
          },
          u = {
            class:
              "flex flex-col p-4 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:text-sm md:font-medium md:border-0 dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700",
          },
          c = ["href", "onClick"],
          h = ["href", "onClick"],
          g = ["href", "onClick"],
          b = ["href", "onClick"];
        const m = {
            __name: "common-navbar",
            setup: function (e) {
              return function (e, t) {
                var o = (0, r.up)("router-link");
                return (
                  (0, r.wg)(),
                  (0, r.iD)("nav", i, [
                    (0, r._)("div", s, [
                      l,
                      (0, r._)("div", d, [
                        (0, r._)("ul", u, [
                          (0, r.Wm)(
                            o,
                            { to: "/", custom: "" },
                            {
                              default: (0, r.w5)(function (e) {
                                var t = e.href,
                                  o = e.route,
                                  n = e.navigate,
                                  i = e.isActive,
                                  s = e.isExactActive;
                                return [
                                  (0, r._)(
                                    "li",
                                    {
                                      class: (0, a.C_)([
                                        "block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:border-0 hover:text-teal-700 dark:text-gray-400 md:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent",
                                        [
                                          i && "router-link-active",
                                          s && "router-link-exact-active",
                                        ],
                                      ]),
                                    },
                                    [
                                      (0, r._)(
                                        "a",
                                        { class: "p-4", href: t, onClick: n },
                                        (0, a.zw)(o.name),
                                        9,
                                        c
                                      ),
                                    ],
                                    2
                                  ),
                                ];
                              }),
                              _: 1,
                            }
                          ),
                          (0, r.Wm)(
                            o,
                            { to: "/collection", custom: "" },
                            {
                              default: (0, r.w5)(function (e) {
                                var t = e.href,
                                  o = e.route,
                                  n = e.navigate,
                                  i = e.isActive,
                                  s = e.isExactActive;
                                return [
                                  (0, r._)(
                                    "li",
                                    {
                                      class: (0, a.C_)([
                                        "block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:border-0 hover:text-teal-700 dark:text-gray-400 md:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent",
                                        [
                                          i && "router-link-active",
                                          s && "router-link-exact-active",
                                        ],
                                      ]),
                                    },
                                    [
                                      (0, r._)(
                                        "a",
                                        { class: "p-4", href: t, onClick: n },
                                        (0, a.zw)(o.name),
                                        9,
                                        h
                                      ),
                                    ],
                                    2
                                  ),
                                ];
                              }),
                              _: 1,
                            }
                          ),
                          (0, r.Wm)(
                            o,
                            { to: "/register", custom: "" },
                            {
                              default: (0, r.w5)(function (e) {
                                var t = e.href,
                                  o = e.route,
                                  n = e.navigate,
                                  i = e.isActive,
                                  s = e.isExactActive;
                                return [
                                  (0, r._)(
                                    "li",
                                    {
                                      class: (0, a.C_)([
                                        "block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:border-0 hover:text-teal-700 dark:text-gray-400 md:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent",
                                        [
                                          i && "router-link-active",
                                          s && "router-link-exact-active",
                                        ],
                                      ]),
                                    },
                                    [
                                      (0, r._)(
                                        "a",
                                        { class: "p-4", href: t, onClick: n },
                                        (0, a.zw)(o.name),
                                        9,
                                        g
                                      ),
                                    ],
                                    2
                                  ),
                                ];
                              }),
                              _: 1,
                            }
                          ),
                          (0, r.Wm)(
                            o,
                            { to: "/admin", custom: "" },
                            {
                              default: (0, r.w5)(function (e) {
                                var t = e.href,
                                  o = e.route,
                                  n = e.navigate,
                                  i = e.isActive,
                                  s = e.isExactActive;
                                return [
                                  (0, r._)(
                                    "li",
                                    {
                                      class: (0, a.C_)([
                                        "block py-2 pl-3 pr-4 rounded hover:bg-gray-100 md:border-0 hover:text-teal-700 dark:text-gray-400 md:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent",
                                        [
                                          i && "router-link-active",
                                          s && "router-link-exact-active",
                                        ],
                                      ]),
                                    },
                                    [
                                      (0, r._)(
                                        "a",
                                        { class: "p-4", href: t, onClick: n },
                                        (0, a.zw)(o.name),
                                        9,
                                        b
                                      ),
                                    ],
                                    2
                                  ),
                                ];
                              }),
                              _: 1,
                            }
                          ),
                        ]),
                      ]),
                    ]),
                  ])
                );
              };
            },
          },
          p = m,
          f = p;
      },
    },
    t = {};
  function o(r) {
    var a = t[r];
    if (void 0 !== a) return a.exports;
    var n = (t[r] = { exports: {} });
    return e[r](n, n.exports, o), n.exports;
  }
  (o.m = e),
    (() => {
      var e = [];
      o.O = (t, r, a, n) => {
        if (!r) {
          var i = 1 / 0;
          for (u = 0; u < e.length; u++) {
            for (var [r, a, n] = e[u], s = !0, l = 0; l < r.length; l++)
              (!1 & n || i >= n) && Object.keys(o.O).every((e) => o.O[e](r[l]))
                ? r.splice(l--, 1)
                : ((s = !1), n < i && (i = n));
            if (s) {
              e.splice(u--, 1);
              var d = a();
              void 0 !== d && (t = d);
            }
          }
          return t;
        }
        n = n || 0;
        for (var u = e.length; u > 0 && e[u - 1][2] > n; u--) e[u] = e[u - 1];
        e[u] = [r, a, n];
      };
    })(),
    (() => {
      o.n = (e) => {
        var t = e && e.__esModule ? () => e["default"] : () => e;
        return o.d(t, { a: t }), t;
      };
    })(),
    (() => {
      o.d = (e, t) => {
        for (var r in t)
          o.o(t, r) &&
            !o.o(e, r) &&
            Object.defineProperty(e, r, { enumerable: !0, get: t[r] });
      };
    })(),
    (() => {
      (o.f = {}),
        (o.e = (e) =>
          Promise.all(
            Object.keys(o.f).reduce((t, r) => (o.f[r](e, t), t), [])
          ));
    })(),
    (() => {
      o.u = (e) => "js/about.f56b6c60.js";
    })(),
    (() => {
      o.miniCssF = (e) => {};
    })(),
    (() => {
      o.g = (function () {
        if ("object" === typeof globalThis) return globalThis;
        try {
          return this || new Function("return this")();
        } catch (e) {
          if ("object" === typeof window) return window;
        }
      })();
    })(),
    (() => {
      o.o = (e, t) => Object.prototype.hasOwnProperty.call(e, t);
    })(),
    (() => {
      var e = {},
        t = "online-library-vue:";
      o.l = (r, a, n, i) => {
        if (e[r]) e[r].push(a);
        else {
          var s, l;
          if (void 0 !== n)
            for (
              var d = document.getElementsByTagName("script"), u = 0;
              u < d.length;
              u++
            ) {
              var c = d[u];
              if (
                c.getAttribute("src") == r ||
                c.getAttribute("data-webpack") == t + n
              ) {
                s = c;
                break;
              }
            }
          s ||
            ((l = !0),
            (s = document.createElement("script")),
            (s.charset = "utf-8"),
            (s.timeout = 120),
            o.nc && s.setAttribute("nonce", o.nc),
            s.setAttribute("data-webpack", t + n),
            (s.src = r)),
            (e[r] = [a]);
          var h = (t, o) => {
              (s.onerror = s.onload = null), clearTimeout(g);
              var a = e[r];
              if (
                (delete e[r],
                s.parentNode && s.parentNode.removeChild(s),
                a && a.forEach((e) => e(o)),
                t)
              )
                return t(o);
            },
            g = setTimeout(
              h.bind(null, void 0, { type: "timeout", target: s }),
              12e4
            );
          (s.onerror = h.bind(null, s.onerror)),
            (s.onload = h.bind(null, s.onload)),
            l && document.head.appendChild(s);
        }
      };
    })(),
    (() => {
      o.r = (e) => {
        "undefined" !== typeof Symbol &&
          Symbol.toStringTag &&
          Object.defineProperty(e, Symbol.toStringTag, { value: "Module" }),
          Object.defineProperty(e, "__esModule", { value: !0 });
      };
    })(),
    (() => {
      o.p = "/";
    })(),
    (() => {
      var e = { 143: 0 };
      (o.f.j = (t, r) => {
        var a = o.o(e, t) ? e[t] : void 0;
        if (0 !== a)
          if (a) r.push(a[2]);
          else {
            var n = new Promise((o, r) => (a = e[t] = [o, r]));
            r.push((a[2] = n));
            var i = o.p + o.u(t),
              s = new Error(),
              l = (r) => {
                if (o.o(e, t) && ((a = e[t]), 0 !== a && (e[t] = void 0), a)) {
                  var n = r && ("load" === r.type ? "missing" : r.type),
                    i = r && r.target && r.target.src;
                  (s.message =
                    "Loading chunk " + t + " failed.\n(" + n + ": " + i + ")"),
                    (s.name = "ChunkLoadError"),
                    (s.type = n),
                    (s.request = i),
                    a[1](s);
                }
              };
            o.l(i, l, "chunk-" + t, t);
          }
      }),
        (o.O.j = (t) => 0 === e[t]);
      var t = (t, r) => {
          var a,
            n,
            [i, s, l] = r,
            d = 0;
          if (i.some((t) => 0 !== e[t])) {
            for (a in s) o.o(s, a) && (o.m[a] = s[a]);
            if (l) var u = l(o);
          }
          for (t && t(r); d < i.length; d++)
            (n = i[d]), o.o(e, n) && e[n] && e[n][0](), (e[n] = 0);
          return o.O(u);
        },
        r = (self["webpackChunkonline_library_vue"] =
          self["webpackChunkonline_library_vue"] || []);
      r.forEach(t.bind(null, 0)), (r.push = t.bind(null, r.push.bind(r)));
    })();
  var r = o.O(void 0, [998], () => o(9651));
  r = o.O(r);
})();
//# sourceMappingURL=app.7fad586a.js.map
