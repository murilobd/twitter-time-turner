<template>
  <div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div
          class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
        >
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  v-for="(col, index) of columns"
                  :key="`simple-table-header-${col.id || index}`"
                  scope="col"
                  :class="
                    col.label !== ''
                      ? 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'
                      : 'relative px-6 py-3'
                  "
                >
                  {{ col.label }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(row, rowIdx) in rows"
                :key="`simple-table-row-${row.id || rowIdx}`"
                :class="rowIdx % 2 === 0 ? 'bg-white' : 'bg-gray-50'"
              >
                <td
                  v-for="colSelector of columnsSelectors"
                  :key="`simple-table-row-selector-${colSelector}`"
                  class="
                    px-6
                    py-4
                    whitespace-nowrap
                    text-sm
                    font-medium
                    text-gray-900
                  "
                >
                  <slot :name="colSelector" :row="row">
                    {{ row[colSelector] }}
                  </slot>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SimpleTable",
  props: {
    rows: {
      type: Array,
      default: () => [],
      required: true,
    },
    columns: {
      type: Array,
      default: () => [],
      required: true,
    },
  },
  computed: {
    columnsSelectors() {
      return this.columns.flatMap((c) => c.selector);
    },
  },
};
</script>
