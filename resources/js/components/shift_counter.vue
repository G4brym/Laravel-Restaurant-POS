<template>
    <li>
        <a v-show="showShift" :class="shiftClass">{{ shiftMessage }}</a>
    </li>
</template>

<script>
    import moment from 'moment';
    export default {
        data: function() {
            return {
                showShift: false,
                shiftMessage: "",
                shiftClass: null
            }
        },
        methods: {
            updateShiftData: function(user) {
                let duration = null;
                let shiftStart = user.last_shift_start;

                if (user.shift_active) {
                    if (this.shiftClass !== "bg-green") {
                        this.shiftClass = "bg-green";
                    }
                    duration = moment.duration(moment().diff(moment(shiftStart)));
                    this.shiftMessage = "Shift started at: " + shiftStart + " | " + "Working time: ";
                }
                else {
                    if (this.shiftClass === "bg-red") {
                        return;
                    } else {
                        this.shiftClass = "bg-red";
                    }
                    let shiftEnd = user.last_shift_end;
                    duration = moment.duration(moment(shiftEnd).diff(moment(shiftStart)));
                    this.shiftMessage = "Shift ended at: " + shiftEnd + " | Worked for: ";
                }

                let days = Math.floor(duration.asDays());
                let hours = Math.floor(duration.asHours() - (days * 24));
                let minutes = Math.floor(duration.asMinutes() - ((days * 24 * 60) + hours * 60));
                let seconds = Math.floor(duration.asSeconds() - (days * 24 * 60 * 60 + hours * 60 * 60 + minutes * 60));

                if (days !== 0) {
                    this.shiftMessage += days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's';
                } else {
                    if (hours !== 0) {
                        this.shiftMessage += hours + 'h ' + minutes + 'm ' + seconds + 's';
                    }
                    else {
                        this.shiftMessage += minutes + 'm ' + seconds + 's';
                    }
                }
            }
        },
        mounted() {
            setInterval(() => {
                if (this.$store.state.user) {
                    this.updateShiftData(this.$store.state.user);
                    this.showShift = true;
                } else {
                    this.showShift = false;

                }
            }, 1000);
        },
    }
</script>
