<template>
    <input type="number" class="form-control" v-bind:class="{ 'form-control-sm': small }" pattern="\d+\.?\d*|\.\d+" :value="value" @input="onInput(value, $event)" @focus="onFocus" :placeholder="placeholder">
</template>

<script>
export default {
    props: [
            'value',
            'small',
            'placeholder',
        ],
    methods: {
        onFocus: function (event) {
            event.target.select()
        },
        onInput: function (value, event) {
            value = this.opt(value, event)
            this.$emit('input', value)
        },
        opt: function (oldVal, event) {
            if (event.target.value == '') return event.target.value
            var re = new RegExp(event.target.pattern);
            var result = re.exec(event.target.value);
            if (oldVal == undefined) oldVal = ''
            return event.target.value = (result) ? result[0] : oldVal;
        }
    },
}
</script>

<style lang="scss" scope>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>