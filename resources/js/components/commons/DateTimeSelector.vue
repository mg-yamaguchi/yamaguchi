<template>
    <div class="d-flex">
        <div class="align-self-center mr-2" v-if="title">{{title}}</div>
        <select class="form-control mr-1" style="width:6rem;" v-model="selected_year" :class="{ 'is-invalid': isInvalid }">
            <option></option>
            <option v-for="year in years" :key="year.value" :value="year.value">{{year.value}}</option>
        </select>
        <!-- <span class="align-self-center mx-1">年</span> -->
        <select class="form-control mr-1" style="width:5rem;"  v-model="selected_month" :class="{ 'is-invalid': isInvalid }">
            <option></option>
            <option v-for="month in months" :key="month.value" :value="month.value">{{month.value}}</option>
        </select>
        <!-- <span class="align-self-center mx-1">月</span> -->
        <select class="form-control mr-2" style="width:5rem;"  v-model="selected_day" :class="{ 'is-invalid': isInvalid }">
            <option></option>
            <option v-for="day in days" :key="day.value" :value="day.value">{{day.value}}</option>
        </select>
        <!-- <span class="align-self-center mx-1">日</span> -->
        <select class="form-control mr-1" style="width:5rem;"  v-model="selected_hour" :class="{ 'is-invalid': isInvalid }">
            <option></option>
            <option v-for="hour in hours" :key="hour.value" :value="hour.value">{{hour.value}}</option>
        </select>
        <select class="form-control" style="width:5rem;"  v-model="selected_minute" :class="{ 'is-invalid': isInvalid }">
            <option></option>
            <option v-for="minute in minutes" :key="minute.value" :value="minute.value">{{minute.value}}</option>
        </select>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    props: {
        title: {
            default: '',
        },
        value: {
            default: '',
        },
        isInvalid: {
            default: false,
        },
        minHour: {
            default: 8,
        },
        maxHour: {
            default: 18,
        },
        minuteInterval: {
            default: 10,
        },
        selectedPastYears: {
            default: 2,
        },
        selectedFutureYears: {
            default: 1,
        }
    },
    data () {
        return {
            selected_year: '',
            selected_month: '',
            selected_day: '',
            selected_hour: '',
            selected_minute: '',
        }
    },
    mounted: function () {
        // console.log(this.value)
        if (this.value) {
            let d = moment(this.value)
            this.selected_year = d.get('year')
            this.selected_month = d.get('month') + 1
            this.selected_day = d.get('date')
            this.selected_hour = d.get('hour')
            this.selected_minute = ('0' + d.get('minute')).slice(-2)
        }
    },
    watch: {
        value: function (val) {
            if (!val) {
                // this.selected_year = ''
                // this.selected_month = ''
                // this.selected_day = ''
                return
            }
            let d = moment(val)
            this.selected_year = d.get('year')
            this.selected_month = d.get('month') + 1
            this.selected_day = d.get('date')
            this.selected_hour = d.get('hour')
            this.selected_minute = ('0' + d.get('minute')).slice(-2)
        },
        selected_year: function () {
            let ret = this.convertDate()
            this.$emit('input', ret)
        },
        selected_month: function () {
            if (this.selected_year && this.selected_month) {
                let d = moment(this.selected_year+'-'+('00' + this.selected_month).slice(-2)+'-01')
                let end = d.endOf('month').get('date')
                if (this.selected_day > end) this.selected_day = end
            }
            let ret = this.convertDate()
            this.$emit('input', ret)
        },
        selected_day: function () {
            let ret = this.convertDate()
            this.$emit('input', ret)
        },
        selected_hour: function () {
            let ret = this.convertDate()
            this.$emit('input', ret)
        },
        selected_minute: function () {
            let ret = this.convertDate()
            this.$emit('input', ret)
        },
    },
    computed: {
        years: function () {
            let years = []
            let start = new moment()
            start.subtract(this.selectedPastYears, 'year')
            for (let i = 1; i <= this.selectedPastYears + this.selectedFutureYears + 1; i++) {
                years.push({
                    value: start.format('YYYY')
                });
                start.add(1, 'years')
            }
            return years
        },
        months: function () {
            let months = []
            for (let i = 1; i <= 12; i++) {
                months.push({
                    value: i
                });
            }
            return months
        },
        days: function () {
            let days = []
            if (!this.selected_year || !this.selected_month) return days
            let d = moment(this.selected_year+'-'+('00' + this.selected_month).slice(-2)+'-01')
            let end = d.endOf('month').get('date')
            for (let i = 1; i <= end; i++) {
                days.push({
                    value: i
                });
            }
            return days
        },
        hours: function () {
            let hours = []
            for (let i = this.minHour; i <= this.maxHour; i++) {
                hours.push({
                    value: i
                });
            }
            return hours
        },
        minutes: function () {
            let minutes = []
            for (let i = 0; i < 60; i = i + this.minuteInterval) {
                minutes.push({
                    value: ('0' + i).slice(-2)
                });
            }
            return minutes
        },
    },
    methods: {
        convertDate: function () {
            let ret = ''
            if (this.selected_year && this.selected_month && this.selected_day) {
                ret = moment(this.selected_year+'-'+('00' + this.selected_month).slice(-2)+'-'+('00' + this.selected_day).slice(-2)+' '+('00' + this.selected_hour).slice(-2)+':'+('00' + this.selected_minute).slice(-2)).format('YYYY-MM-DD HH:mm')
            }
            return ret
        }
    }
}
</script>