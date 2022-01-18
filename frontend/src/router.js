import { createRouter, createWebHistory } from 'vue-router';
import Tweets from "./views/tweets/tweets.vue";
import NewTweet from "./views/new-tweet/new-tweet.vue";

const routes = [
    {
        path: "/",
        name: "tweets",
        component: Tweets
    },
    {
        path: "/new-tweet",
        name: "new-tweet",
        component: NewTweet
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});

export default router;
