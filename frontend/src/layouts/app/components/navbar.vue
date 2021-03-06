<template>
    <Disclosure as="nav" class="bg-gray-800" v-slot="{ open }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img
                            class="h-8 w-8"
                            src="https://tailwindui.com/img/logos/workflow-mark-indigo-500.svg"
                            alt="Twitter Time Turner"
                        />
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <router-link
                                v-for="item in navigation"
                                :key="item.name"
                                :to="{ name: item.routeName }"
                                :class="[
                                    item.routeName === currentRouteName
                                        ? 'bg-gray-900 text-white'
                                        : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                                    'px-3 py-2 rounded-md text-sm font-medium',
                                ]"
                                :aria-current="item.current ? 'page' : undefined"
                            >{{ item.name }}</router-link>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- Profile dropdown -->
                        <Menu as="div" class="ml-3 relative" v-if="user">
                            <div>
                                <MenuButton
                                    class="max-w-xs bg-gray-800 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                                >
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" :src="userPicture" alt />
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
                                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                >
                                    <MenuItem
                                        v-for="item in userNavigation"
                                        :key="item.name"
                                        v-slot="{ active }"
                                    >
                                        <a
                                            :href="item.href"
                                            :class="[
                                                active ? 'bg-gray-100' : '',
                                                'block px-4 py-2 text-sm text-gray-700',
                                            ]"
                                        >{{ item.name }}</a>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
                <div class="-mr-2 flex md:hidden">
                    <!-- Mobile menu button -->
                    <DisclosureButton
                        class="bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                    >
                        <span class="sr-only">Open main menu</span>
                        <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                        <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>
            </div>
        </div>

        <DisclosurePanel class="md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <router-link
                    v-for="item in navigation"
                    :to="{ name: item.routeName }"
                    :key="item.name"
                    :class="[
                        item.routeName === currentRouteName
                            ? 'bg-gray-900 text-white'
                            : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                        'block px-3 py-2 rounded-md text-base font-medium',
                    ]"
                    :aria-current="
                        item.routeName === currentRouteName ? 'page' : undefined
                    "
                >
                    <DisclosureButton>{{ item.name }}</DisclosureButton>
                </router-link>
            </div>
            <div class="pt-4 pb-3 border-t border-gray-700" v-if="user">
                <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" :src="user.twitter_avatar" alt />
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium leading-none text-white">{{ user.name }}</div>
                        <div class="text-sm font-medium leading-none text-gray-400">{{ user.email }}</div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <DisclosureButton
                        v-for="item in userNavigation"
                        :key="item.name"
                        as="a"
                        :href="item.href"
                        class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700"
                    >{{ item.name }}</DisclosureButton>
                </div>
            </div>
        </DisclosurePanel>
    </Disclosure>
</template>

<script>
import { useRoute } from "vue-router";

import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from "@headlessui/vue";
import { MenuIcon, XIcon } from "@heroicons/vue/outline";
import useUser from "../../../composables/useUser.js";
import { computed } from "vue";

const navigation = [
    { name: "Tweets", routeName: "tweets" },
    { name: "+ Schedule a new Tweet", routeName: "new-tweet" },
];
const userNavigation = [
    { name: "Sign out", href: "#" },
];

export default {
    name: "Navbar",
    components: {
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
        Menu,
        MenuButton,
        MenuItem,
        MenuItems,
        MenuIcon,
        XIcon,
    },
    setup() {
        const route = useRoute();
        const { user: mainUser } = useUser();

        const userPicture = computed(() => {
            return mainUser.value.twitter_avatar?.replace(/^http:\/\//gi, "https://");
        })

        return {
            user: mainUser.value,
            userPicture,
            navigation,
            userNavigation,
            currentRouteName: route.name,
        };
    },
};
</script>
