<script setup lang="ts">
import AppLayout from "@/layouts/AppLayout.vue";
import { useForm } from "vee-validate";
import { Icon } from "@iconify/vue";
import { object, string } from "yup";

const schema = object({
  package: string().required().max(255).min(3),
  "tracking-code": string().required().length(13)
});
</script>

<template>
  <AppLayout>
    <div class="mx-3 my-3 flex justify-center md:mx-6 md:my-6">
      <div
        class="flex w-full max-w-7xl flex-col justify-center gap-3 space-y-2 rounded-md bg-white p-6 shadow"
      >
        <Form
          method="POST"
          class="max-w-xl space-y-3"
          @submit="handleNewPackage"
          :validation-schema="schema"
        >
          <div>
            <label for="package" class="block text-sm font-medium leading-6 text-gray-900">
              Package*
            </label>
            <div class="mt-1">
              <Field
                id="package"
                name="package"
                type="text"
                maxlength="13"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-400 sm:text-sm sm:leading-6"
              />
              <ErrorMessage name="package" class="text-xs text-red-500" />
            </div>
          </div>
          <div>
            <label for="tracking-code" class="block text-sm font-medium leading-6 text-gray-900">
              Tracking code*
            </label>
            <div class="mt-1">
              <Field
                id="tracking-code"
                name="tracking-code"
                type="text"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-400 sm:text-sm sm:leading-6"
              />
              <ErrorMessage name="tracking-code" class="text-xs text-red-500" />
            </div>
          </div>
          <div>
            <label for="description" class="block text-sm font-medium leading-6 text-gray-900">
              Description
            </label>
            <div class="mt-1">
              <textarea
                id="description"
                name="description"
                type="text"
                maxlength="255"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-yellow-400 sm:text-sm sm:leading-6"
              ></textarea>
            </div>
          </div>
          <p class="text-xs text-gray-400">* Required fields</p>
          <button
            type="submit"
            class="inline-flex w-max items-center gap-1 rounded-md bg-green-500 px-3 py-1 text-lg font-bold text-white"
          >
            <Icon icon="mdi:check" />
            Save
          </button>
        </Form>
      </div>
    </div>
  </AppLayout>
</template>
