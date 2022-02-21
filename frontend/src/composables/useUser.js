import { toRef, ref, computed, readonly } from "vue";

export default function useUser() {
    const user = ref(JSON.parse(localStorage.getItem("user") || null));

    const setUser = newUser => {
        user.value = newUser;
        localStorage.setItem("user", JSON.stringify(newUser));
    }

    const isAuthenticated = computed(() => {
        return !!user.value?.id
    });
    const authorizedTwitterToPost = computed(() => !!user.value?.twitter_oauth_ok);

    return {
        user: user,
        setUser,
        isAuthenticated,
        authorizedTwitterToPost
    }
}
