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
                            <p
                                class="mt-2 text-sm text-gray-500"
                            >You still have {{ leftChars }} chars to type</p>
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
                                    @update:modelValue="publishNow = false"
                                    autoApply
                                    :minDate="new Date()"
                                    showNowButton
                                >
                                    <template #now-button="{ selectCurrentDate }">
                                        <span
                                            class="cursor-pointer m-4 px-4 border rounded border-indigo-400"
                                            @click="clickedPublishNow(selectCurrentDate)"
                                            title="Now"
                                        >Now</span>
                                    </template>
                                </Datepicker>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-5">
                    <div class="flex justify-end space-x-2">
                        <ttt-button inverted @click="resetForm">Cancel</ttt-button>
                        <ttt-button type="submit">Schedule tweet</ttt-button>
                    </div>
                </div>
            </div>
        </form>

        <TttSimpleModal ref="modal" :type="modalContent.type">
            <template #title>{{ modalContent.title }}</template>
            {{ modalContent.content }}
            <template #button>{{ modalContent.button }}</template>
        </TttSimpleModal>
    </layout-app>
</template>

<script setup>
import { ref, computed } from "vue";
import Datepicker from "vue3-date-time-picker";
import "vue3-date-time-picker/dist/main.css";
import { fetcher } from "../../helpers/http.js";
import timezones from "../../helpers/timezones.js";
import dayjs from "../../helpers/dayjs.js";

// Automatically fill timezone for user
const guessedTimezone = dayjs.tz.guess();
const timezone = ref(timezones.includes(guessedTimezone) ? guessedTimezone : "Europe/Paris");
const date = ref(new Date());
const publishNow = ref(true);
const content = ref("");
const modal = ref(null);
const modalContent = ref({
    type: 'success',
    title: "Yeah!",
    content: 'Tweet scheduled!',
    button: 'Ok! Thanks!'
});
const postTweet = async () => {
    try {
        const final = {
            tweet: content.value,
            timezone: timezone.value,
            publish_datetime: dayjs(date.value).format("YYYY-MM-DD HH:mm:ss"),
            publish_now: publishNow.value
        }
        const fetchData = await fetcher(`/api/tweets`, {
            method: 'POST',
            body: final
        });
        console.log(fetchData);
        modalContent.value = {
            type: 'success',
            title: "Yeah!",
            content: 'Tweet scheduled!',
            button: 'Ok! Thanks!'
        };
        await modal.value.openModal();
        resetForm();
    } catch (error) {
        let errorMessage = error?.data?.message || "Ops! Something went wrong!";
        if (error?.status === 422 && error?.data?.errors) {
            errorMessage = Object.values(error?.data?.errors).flatMap(err => err).join(" ")
        }
        modalContent.value = {
            type: 'danger',
            title: "Ops!",
            content: errorMessage,
            button: 'Close'
        };
        await modal.value.openModal();
    }
}

const availableTimezones = computed(() => timezones);
const leftChars = computed(() => 140 - (content.value?.length || 0))

const resetForm = () => {
    date.value = new Date();
    timezone.value = guessedTimezone;
    content.value = "";
}

// that's silly, but when user click on the NOW button from datepicker, it will change the date value and set the publishNow to false. With the timeout, it will set publishNow to true.
const clickedPublishNow = fn => {
    fn(); // this is the internal method from datepicker that should be called to update the date
    setTimeout(() => publishNow.value = true, 300)
}
</script>
