<template>
    <button :type="type" :class="buttonClass">
        <slot />
    </button>
</template>

<script>
export default {
    name: "TTTButton",

    props: {
        type: {
            type: String,
            default: "button"
        },

        color: {
            type: String,
            default: "indigo"
        },

        inverted: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        buttonClass() {
            if (this.inverted) {
                return `flex justify-center bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-${this.color}-500`
            }

            return `flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 bg-${this.color}-600 hover:bg-indigo-700 hover:bg-${this.color}-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-${this.color}-500`;
        }
    }
}
</script>
