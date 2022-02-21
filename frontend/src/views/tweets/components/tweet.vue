<template>
    <div class="flex align-middle justify-center bg-slate-50 rounded-lg p-4 w-full">
        <div class="flex-auto p-4">
            <h3 class="pr-10 font-semibold text-gray-900 xl:pr-0">{{ tweet.tweet }}</h3>
            <dl class="mt-2 flex flex-col text-gray-500 xl:flex-row">
                <div class="flex items-start space-x-3">
                    <dt class="mt-0.5">
                        <span class="sr-only">Date</span>
                        <CalendarIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                    </dt>
                    <dd>
                        <time
                            :datetime="tweet.publish_datetime"
                        >{{ dayjs(tweet.publish_datetime).format("llll") }}</time>
                    </dd>
                </div>
                <div
                    class="mt-2 flex items-start space-x-3 xl:mt-0 xl:ml-3.5 xl:border-l xl:border-gray-400 xl:border-opacity-50 xl:pl-3.5"
                >
                    <dt class="mt-0.5">
                        <span class="sr-only">Is published?</span>
                        <svg
                            width="20"
                            height="20"
                            fill="currentColor"
                            class="h-5 w-5"
                            :class="tweet.is_published ? 'text-cyan-500' : 'text-gray-400'"
                        >
                            <path
                                d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"
                            />
                        </svg>
                    </dt>
                    <dd>{{ boolToText(tweet.is_published) }}</dd>
                </div>
            </dl>
        </div>
        <div class="flex">
            <MenuTweet
                :is-published="tweet.is_published"
                @deleteTweet="deleteTweet()"
                @viewTweet="viewTweet()"
            />
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';
import dayjs from "dayjs";
import { CalendarIcon, EyeIcon } from "@heroicons/vue/solid";
import MenuTweet from "./menu-tweet.vue";
import { fetcher } from '../../../helpers/http';
import useUser from '../../../composables/useUser';

const props = defineProps({
    tweet: Object
});

const emits = defineEmits(["tweet-deleted"])

const { user } = useUser();

async function deleteTweet() {
    try {
        await fetcher(`/api/tweets/${props.tweet.id}`, {
            method: 'DELETE'
        });
        alert("Tweet deleted. It won't be posted anymore.");
        emits('tweet-deleted', props.tweet.id);
    } catch (error) {
        console.log(error);
        alert("Failed excluding tweet.");
    }
}

function viewTweet() {
    window.open(`https://twitter.com/${user.value.twitter_user_id}/status/${props.tweet.tweet_id}`, "_blank");
}

function boolToText(value) {
    return value ? "Yes" : "No"
}
</script>
