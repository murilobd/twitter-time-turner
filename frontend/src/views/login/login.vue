<template>
    <layout-auth>
        <template v-slot:title>Twitter Time Turner</template>
        <template v-slot:subtitle>Sign in using your Twitter account</template>
        <form class="space-y-6" @submit.prevent="redirectToLogin">
            <div>
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300" />
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Sign in using your Twitter account</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3">
                    <twitter-login-button />
                </div>
            </div>
        </form>
    </layout-auth>
</template>

<script>
import TwitterLoginButton from "./components/twitter-login-button.vue"

import { onMounted } from "vue";
import { useRouter, useRoute } from 'vue-router'
import useUser from "../../composables/useUser.js";
import { fetcher } from "../../helpers/http.js";

export default {
    name: "LoginView",

    setup() {
        const backendUrl = import.meta.env.VITE_BACKEND_URL;
        const accessTokenKey = import.meta.env.VITE_ACCESS_TOKEN_KEY
        const { setUser } = useUser();
        const route = useRoute();
        const router = useRouter();

        const redirectToLogin = () => {
            window.location.href = `${backendUrl}/login/twitter`;
        }

        const loginUser = async () => {
            try {
                const dataUser = await fetcher("/api/user")
                setUser(dataUser.data);
                return dataUser.data;
            } catch (error) {
                console.error("Failed authenticating user", error);
                return null;
            }
        }

        onMounted(async () => {
            // check if in url there's a query param called "access-token" to login user or if there's an access token ready to be used
            const accessToken = route.query[accessTokenKey] || localStorage.getItem(accessTokenKey);
            if (accessToken) {
                localStorage.setItem(accessTokenKey, accessToken);
                const user = await loginUser();
                // if fails authenticating
                // TODO: improve this to display error message to user
                if (!user) {
                    alert("Not authenticated");
                    return;
                }
                // TODO: improe to show error message if fails getting twitter authorization to post on behalf user
                if (!user.twitter_oauth_ok) {
                    window.location.href = `${backendUrl}/request_tweeter_oauth`;
                    return;
                }

                // logged in! uhul!
                router.push({
                    path: "/"
                });
            }
        })


        return {
            redirectToLogin
        }
    },

    components: {
        TwitterLoginButton
    }
}
</script>
