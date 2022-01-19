<template>
    <layout-auth>
        <template v-slot:title>Sign in to your account</template>
        <template v-slot:subtitle>
            Or
            {{ ' ' }}
            <router-link
                :to="{ name: 'register' }"
                class="font-medium text-indigo-600 hover:text-indigo-500"
            >Create a new one</router-link>
        </template>
        <form class="space-y-6" action="#" method="POST">
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
                        autocomplete="current-password"
                        required
                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        id="remember-me"
                        name="remember-me"
                        type="checkbox"
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    />
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>

                <div class="text-sm">
                    <a
                        href="#"
                        class="font-medium text-indigo-600 hover:text-indigo-500"
                    >Forgot your password?</a>
                </div>
            </div>

            <div>
                <ttt-button type="submit" class="w-full">Sign in</ttt-button>
            </div>
        </form>
    </layout-auth>
</template>
