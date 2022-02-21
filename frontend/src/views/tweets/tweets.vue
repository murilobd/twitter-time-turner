<template>
    <layout-app>
        <template #title>Tweets</template>

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-16">
            <calendar @changed-month="getTweets" />
            <div class="divide-y lg:col-span-7 xl:col-span-8" v-if="loading">
                <div class="flex justify-center p-4">
                    <TttLoading />
                </div>
            </div>
            <ol
                class="divide-y divide-gray-100 text-sm leading-6 lg:col-span-7 xl:col-span-8"
                v-else
            >
                <li
                    v-for="tweet in tweets"
                    :key="tweet.id"
                    class="relative flex space-x-6 py-6 xl:static"
                >
                    <tweet :tweet="tweet" @tweet-deleted="removeTweetFromList($event)" />
                </li>
                <li v-if="tweets.length === 0">
                    <span
                        class="mt-2 block text-lg font-light text-gray-900 text-center pb-4"
                    >You don't have any tweet scheduled for the selected period</span>
                </li>
                <li>
                    <router-link
                        :to="{ name: 'new-tweet' }"
                        type="button"
                        class="relative block w-full border-2 border-gray-300 border-dashed rounded-lg p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            stroke="currentColor"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                            aria-hidden="true"
                            class="mx-auto h-12 w-12 text-cyan-500"
                        >
                            <path
                                d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"
                            />
                        </svg>
                        <span
                            class="mt-2 block text-sm font-medium text-gray-900"
                        >Schedule new Tweet</span>
                    </router-link>
                </li>
            </ol>
        </div>
    </layout-app>
</template>

<script>
import { onMounted, ref } from "vue";
import { fetcher } from "../../helpers/http.js";
import dayjs from "dayjs";
import Calendar from "./components/calendar.vue";
import Tweet from "./components/tweet.vue";

export default {
    name: "Tweets",
    components: { Calendar, Tweet },

    setup() {
        const tweets = ref([]);
        const loading = ref(false);

        const getTweets = async ({ month = null, year = null }) => {
            try {
                loading.value = true;
                const tweetsFetch = await fetcher(`/api/tweets?month=${month}&year=${year}`);
                const data = tweetsFetch.data.data.map(tweet => ({
                    ...tweet,
                    publish_on: dayjs.tz(tweet.publish_datetime, tweet.timezone)
                }))
                tweets.value = data;
            } catch (error) {
                console.log(error);
                alert("Failed getting tweets");
            } finally {
                loading.value = false;
            }
        }

        const removeTweetFromList = tweetId => {
            tweets.value = tweets.value.filter(tweet => tweet.id !== tweetId);
        }

        onMounted(async () => {
            const today = dayjs();
            await getTweets({
                month: today.format("MM"),
                year: today.year()
            });
        });

        return {
            columns: [
                {
                    label: "Tweet",
                    selector: "tweet",
                },
                {
                    label: "Published?",
                    selector: "is_published",
                },
                {
                    label: "Date",
                    selector: "publish_on",
                },
            ],
            tweets,
            getTweets,
            loading,
            removeTweetFromList
        };
    },
};
</script>
