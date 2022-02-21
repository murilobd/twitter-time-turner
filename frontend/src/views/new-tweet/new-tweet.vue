<template>
    <layout-app>
        <template #title>New Tweet</template>

        <form class="space-y-8 divide-y divide-gray-200" @submit.prevent="postTweet">
            <div class="space-y-8 divide-y divide-gray-200">
                <div>
                    <!-- Header 1 -->
                    <div>
                        <h3
                            class="text-lg leading-6 font-medium text-gray-900"
                        >What's on your mind, my friend?</h3>
                        <p
                            class="mt-1 text-sm text-gray-500"
                        >Tell the world in 140 chars what's going on!</p>
                    </div>

                    <!-- Tweet textarea -->
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label for="about" class="block text-sm font-medium text-gray-700">Tweet</label>
                            <div class="mt-1">
                                <textarea
                                    v-model="content"
                                    id="tweet"
                                    name="tweet"
                                    rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                />
                            </div>
                            <p class="mt-2 text-sm text-gray-500">You still have XXXX chars to type</p>
                        </div>
                    </div>
                </div>

                <div class="pt-8">
                    <!-- Header schedule -->
                    <div>
                        <h3
                            class="text-lg leading-6 font-medium text-gray-900"
                        >When do you want this to be tweeted?</h3>
                        <p
                            class="mt-1 text-sm text-gray-500"
                        >Set the timezone, date and time for this tweet to be tweeted.</p>
                    </div>
                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label
                                for="timezone"
                                class="block text-sm font-medium text-gray-700"
                            >Timezone</label>
                            <div class="mt-1">
                                <select
                                    id="timezone"
                                    name="timezone"
                                    autocomplete="timezone-name"
                                    v-model="timezone"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                >
                                    <option
                                        v-for="timezone of availableTimezones"
                                        :value="timezone"
                                    >{{ timezone }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <label
                                for="datepicker"
                                class="block text-sm font-medium text-gray-700"
                            >When?</label>
                            <div class="mt-1">
                                <Datepicker
                                    v-model="date"
                                    autoApply
                                    :minDate="new Date()"
                                    :showNowButton="true"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end space-x-2">
                        <ttt-button inverted @click="getUser">Cancel</ttt-button>
                        <ttt-button type="submit">Schedule tweet</ttt-button>
                    </div>
                </div>
            </div>
        </form>
    </layout-app>
</template>

<script>
import { ref } from "vue";
import Datepicker from "vue3-date-time-picker";
import "vue3-date-time-picker/dist/main.css";
import { fetcher } from "../../helpers/http.js";
import timezones from "../../helpers/timezones.js";
import dayjs from "../../helpers/dayjs.js";

export default {
    name: "NewTweet",
    components: {
        Datepicker
    },

    data() {
        return {
            date: new Date(),
            content: "",
            timezone: "Europe/Paris"
        };
    },

    methods: {
        async postTweet() {
            try {
                const final = {
                    tweet: this.content,
                    timezone: this.timezone,
                    publish_datetime: dayjs(this.date).format("YYYY-MM-DD HH:mm:ss")
                }
                const fetchData = await fetcher(`/api/tweets`, {
                    method: 'POST',
                    body: final
                });
                console.log(fetchData);
                alert("Uhul!");
            } catch (error) {
                alert("ops!");
                console.log(error);
            }
        }
    },

    computed: {
        availableTimezones() {
            return timezones;
        }
    }

    // setup() {
    //     const date = ref(new Date());
    //     const content = ref("");

    //     async function postTweet() {
    //         try {
    //             const backendUrl = import.meta.env.VITE_BACKEND_URL;
    //             const fetchData = await fetcher(`${backendUrl}/twitter`, {
    //                 method: 'POST',
    //                 body: JSON.parse({
    //                     content: content.value
    //                 })
    //             });
    //             console.log(fetchData);
    //             alert("Uhul!");
    //         } catch (error) {
    //             alert("ops!");
    //             console.log(error);
    //         }
    //     }

    //     return {
    //         date,
    //         content,
    //         postTweet
    //     };
    // },
};
</script>
