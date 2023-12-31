import type { Config } from "tailwindcss";

import typography from "@tailwindcss/typography";
import forms from "@tailwindcss/forms";

export default {
  content: ["./index.html", "./src/**/*.{vue,js,ts}"],
  theme: {
    extend: {}
  },
  plugins: [typography, forms]
} satisfies Config;
