<template>
    <li>
        <a :class="shiftClass">{{ shiftMessage + valueMessage }}</a>
    </li>
</template>

<script>
    import moment from 'moment';
    import countdown from 'countdown';
    export default {
        data: function() {
            return {
                shiftMessage: "",
                valueMessage: "",
                shiftClass: null,
                interval: null,
            }
        },
        methods: {
            updateCounter: function(user) {
                if (user.shift_active) {
                    this.shiftClass = "bg-green";
                    this.shiftMessage = "Shift started at: " + user.last_shift_start + " | " + "Working time: ";

                    this.interval = countdown(moment(user.last_shift_start), (ts) => {
                        this.valueMessage = ts.toString();
                    }, countdown.DEFAULTS);

                } else {
                    if (this.interval) {
                        clearInterval(this.interval);
                    }

                    this.shiftClass = "bg-red";
                    this.shiftMessage = "Shift ended at: " + user.last_shift_end + " | Worked for: ";
                    this.valueMessage =
                        countdown(moment(user.last_shift_start), moment(user.last_shift_end), countdown.DEFAULTS).toString();
                }
            }
        },
        mounted() {
            countdown.setFormat(
                {
                    'singular': '|s|m |h |d ',
                    'plural': '|s|m |h |d ',
                    'last': ''
                }
            );

            this.updateCounter(this.$store.state.user);
        },
        beforeDestroy() {
            if (this.interval) {
                clearInterval(this.interval);
            }
        }
    }
</script>
