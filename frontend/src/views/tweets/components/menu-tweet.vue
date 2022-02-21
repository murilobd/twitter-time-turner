<template>
    <Menu
        as="div"
        class="absolute top-6 right-0 xl:relative xl:top-auto xl:right-auto xl:self-center"
    >
        <div>
            <MenuButton
                class="-m-2 flex items-center rounded-full p-2 text-gray-500 hover:text-gray-600"
            >
                <span class="sr-only">Open options</span>
                <DotsHorizontalIcon class="h-5 w-5" aria-hidden="true" />
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <MenuItems
                class="focus:outline-none absolute right-0 z-10 mt-2 w-36 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5"
            >
                <div class="py-1">
                    <MenuItem v-slot="{ active }" v-if="isPublished">
                        <button
                            :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm w-full']"
                            @click="$emit('view-tweet')"
                        >View tweet</button>
                    </MenuItem>
                    <MenuItem v-slot="{ active }" v-else>
                        <button
                            :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm w-full']"
                            @click="$emit('delete-tweet')"
                        >Delete</button>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { DotsHorizontalIcon } from "@heroicons/vue/solid";

defineProps(['isPublished']);
</script>
