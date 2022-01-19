<template>
    <layout-auth>
        <template v-slot:title>Create a new account</template>
        <template v-slot:subtitle>
            Or
            {{ ' ' }}
            <router-link
                :to="{ name: 'login' }"
                class="font-medium text-indigo-600 hover:text-indigo-500"
            >Sign-in if you already have one</router-link>
        </template>
        <form class="space-y-6" action="#" method="POST">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <div class="mt-1">
                    <input
                        id="name"
                        name="name"
                        type="name"
                        autocomplete="name"
                        required
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <div class="mt-1">
                    <input
                        id="email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                </div>
            </div>

            <div>
                <ttt-button type="submit" class="w-full">Create account</ttt-button>
            </div>
        </form>
    </layout-auth>
</template>
