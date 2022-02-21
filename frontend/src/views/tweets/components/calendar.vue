<template>
    <div class="text-center lg:col-start-8 lg:col-end-13 lg:row-start-1 xl:col-start-9">
        <div class="flex items-center text-gray-900">
            <button
                type="button"
                class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500"
                @click="changeMonth(-1)"
            >
                <span class="sr-only">Previous month</span>
                <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
            </button>
            <div class="flex-auto font-semibold">{{ monthNameAndYear }}</div>
            <button
                type="button"
                class="-m-1.5 flex flex-none items-center justify-center p-1.5 text-gray-400 hover:text-gray-500"
                @click="changeMonth(1)"
            >
                <span class="sr-only">Next month</span>
                <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
            </button>
        </div>
        <div class="mt-6 grid grid-cols-7 text-xs leading-6 text-gray-500">
            <div>S</div>
            <div>M</div>
            <div>T</div>
            <div>W</div>
            <div>T</div>
            <div>F</div>
            <div>S</div>
        </div>
        <div
            class="isolate mt-2 grid grid-cols-7 gap-px rounded-lg bg-gray-200 text-sm shadow ring-1 ring-gray-200"
        >
            <button
                v-for="(day, dayIdx) in days"
                :key="day.date"
                type="button"
                :class="['py-1.5 hover:bg-gray-100 focus:z-10', day.isCurrentMonth ? 'bg-white' : 'bg-gray-50', (day.isSelected || day.isToday) && 'font-semibold', day.isSelected && 'text-white', !day.isSelected && day.isCurrentMonth && !day.isToday && 'text-gray-900', !day.isSelected && !day.isCurrentMonth && !day.isToday && 'text-gray-400', day.isToday && !day.isSelected && 'text-indigo-600', dayIdx === 0 && 'rounded-tl-lg', dayIdx === 6 && 'rounded-tr-lg', dayIdx === days.length - 7 && 'rounded-bl-lg', dayIdx === days.length - 1 && 'rounded-br-lg']"
            >
                <time
                    :datetime="day.date"
                    :class="['mx-auto flex h-7 w-7 items-center justify-center rounded-full', day.isSelected && day.isToday && 'bg-indigo-600', day.isSelected && !day.isToday && 'bg-gray-900']"
                >{{ day.date.split('-').pop().replace(/^0/, '') }}</time>
            </button>
        </div>
    </div>
</template>

<script>

import {
    ChevronLeftIcon,
    ChevronRightIcon,
} from '@heroicons/vue/solid'
import dayjs from 'dayjs';
import { ref, computed, watch } from 'vue';


export default {
    name: "Calendar",

    components: {
        ChevronLeftIcon,
        ChevronRightIcon
    },

    emits: ['changedMonth'],

    setup(props, ctx) {
        // start currentDate as today
        const currentDate = ref(dayjs());
        watch(currentDate, async newDate => {
            ctx.emit("changedMonth", {
                month: newDate.format("MM"),
                year: newDate.year()
            })
        })

        // array of days to display in calendar
        const days = computed(() => {
            // get month from current date
            const selectedMonth = dayjs().month(currentDate.value.month()).year(currentDate.value.year());
            const firstDayOfMonth = selectedMonth.startOf('month');
            // from the 1st day of selected month, subtract the weekdays (0 is Sunday, 6 is Saturday) to find out what is the date on the 1st position of calendar
            const firstDayCalendar = firstDayOfMonth.subtract(firstDayOfMonth.day(), 'days');

            const days = [];
            // 42 is the total number of days that will be displayed in the calendar
            let day = firstDayCalendar;
            for (let dayIdx = 0; dayIdx < 42; dayIdx++) {
                day = day.add(1, 'days');
                days.push({
                    date: day.format("YYYY-MM-DD"),
                    isCurrentMonth: day.isSame(selectedMonth, 'month'),
                    isToday: day.isSame(dayjs(), 'day')
                });
            }
            return days;
        });

        const monthNameAndYear = computed(() => currentDate.value.format("MMMM YYYY"));

        // Add/subtract month from the current date
        const changeMonth = (qttMonths = 1) => {
            currentDate.value = dayjs()
                .month(currentDate.value.month())
                .year(currentDate.value.year())
                .add(qttMonths, 'month');
        }

        return {
            days,
            monthNameAndYear,
            changeMonth
        }
    }
}
</script>
