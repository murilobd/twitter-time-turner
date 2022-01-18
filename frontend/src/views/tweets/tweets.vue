<template>
  <layout-app>
    <template #title> Tweets </template>

    <simple-table :rows="rows" :columns="columns">
      <template v-slot:published="{ row }">
        <span
          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
          :class="
            row.published
              ? 'bg-green-100 text-green-800'
              : 'bg-red-100 text-red-800'
          "
        >
          {{ row.published ? "Yes" : "No" }}
        </span>
      </template>
    </simple-table>
  </layout-app>
</template>

<script>
export default {
  name: "Tweets",
  components: {},

  setup() {
    return {
      columns: [
        {
          label: "Tweet",
          selector: "tweet",
        },
        {
          label: "Published?",
          selector: "published",
        },
        {
          label: "Date",
          selector: "date",
        },
      ],
      rows: [
        { id: 1, tweet: "Testing a tweet", published: false, date: new Date() },
        {
          id: 2,
          tweet: "Testing a tweet 2",
          published: true,
          date: new Date(),
        },
        {
          id: 3,
          tweet: "Testing a tweet 3",
          published: false,
          date: new Date(),
        },
      ],
    };
  },
};
</script>
